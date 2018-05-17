<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Score;
use BackendBundle\Entity\Notification;

class ScoreController extends Controller
{

    //---------------------INDEX------------------------------

    public function indexAction(){
        
        $con = $this->getDoctrine()->getManager();
        $score = $con->getRepository('BackendBundle:Score')->findAll();

        $delete_form = $this->createCustomForm(':ID','DELETE','score_delete');

        return $this->render('score/index.html.twig', array('score' => $score,'delete_form' => $delete_form->createView() ));

    }

    //---------------------EDIT------------------------------

    public function editAction(Request $request, Score $score){
        
        $editForm = $this->createForm('BackendBundle\Form\ScoreType', $score);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $score->setStatus(1);
            $match = $this->getDoctrine()->getManager()->getRepository('BackendBundle:Match')->findOneByScore($score->getId());
            $match->setStatus(1);
            $this->getDoctrine()->getManager()->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha editado el score del partido (id:".$score->getId().")");
            $notification->setType("edit");
            $notification->setEntity("score");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('match_index', array('id' => $score->getId()));
        }

        return $this->render('score/edit.html.twig', array(
            'score' => $score,
            'edit_form' => $editForm->createView(),
        ));

    }

    //---------------------NEW------------------------------

    public function newAction(Request $request)
    {
        $score = new Score();
        $form = $this->createForm('BackendBundle\Form\Score', $score);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $score->setStatus(1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($score);
            $em->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha agregado el score del partido (id:".$score->getId().")");
            $notification->setType("add");
            $notification->setEntity("score");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('score_index');
        }

        return $this->render('score/new.html.twig', array(
            'score' => $score,
            'form' => $form->createView(),
        ));
    }

    //---------------------DELETE------------------------------

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $score = $em->getRepository('BackendBundle:Score')->find($id);

        $form = $this->createCustomForm($score->getId(),'DELETE', 'score_delete');
        $form->handleRequest($request);

        $em->remove($score) ;
        $em->flush();

        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
        ." ha borrado el score del partido (id:".$score->getId().")");
        $notification->setType("delete");
        $notification->setEntity("score");
        $notification->setEnvironment("Backend");
        $notification->setUser($this->container->get('security.context')->getToken()->getUser());
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('score_index');

    }

    //---------------------FORMS------------------------------

    private function createCustomForm($id,$method,$route){
        return $this->createFormBuilder()->setAction($this->generateUrl($route, array ('id' => $id)))->setMethod($method)->getForm();
    }
}