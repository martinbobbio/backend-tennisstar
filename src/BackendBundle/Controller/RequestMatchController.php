<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\RequestMatch;
use BackendBundle\Entity\Notification;

class RequestMatchController extends Controller
{

    //---------------------INDEX------------------------------

    public function indexAction(){
        
        $con = $this->getDoctrine()->getManager();
        $request_match = $con->getRepository('BackendBundle:RequestMatch')->findAll();

        $delete_form = $this->createCustomForm(':ID','DELETE','request_match_delete');

        return $this->render('requestmatch/index.html.twig', array('request_match' => $request_match,'delete_form' => $delete_form->createView() ));

    }

    //---------------------EDIT------------------------------

    public function editAction(Request $request, RequestMatch $request_match){
        
        $editForm = $this->createForm('BackendBundle\Form\RequestMatchType', $request_match);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha editado la solicitud de partido (id:".$request_match->getId().")");
            $notification->setType("edit");
            $notification->setEntity("requestMatch");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('request_match_index', array('id' => $request_match->getId()));
        }

        return $this->render('requestmatch/edit.html.twig', array(
            'request_match' => $request_match,
            'edit_form' => $editForm->createView(),
        ));

    }

    //---------------------NEW------------------------------

    public function newAction(Request $request)
    {
        $request_match = new RequestMatch();
        $form = $this->createForm('BackendBundle\Form\RequestMatchType', $request_match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($request_match);
            $em->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha agregado la solicitud de partido (id:".$request_match->getId().")");
            $notification->setType("add");
            $notification->setEntity("requestMatch");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('request_match_index');
        }

        return $this->render('requestmatch/new.html.twig', array(
            'request_match' => $request_match,
            'form' => $form->createView(),
        ));
    }

    //---------------------DELETE------------------------------

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $request_match = $em->getRepository('BackendBundle:RequestMatch')->find($id);

        $form = $this->createCustomForm($request_match->getId(),'DELETE', 'request_match_delete');
        $form->handleRequest($request);

        $em->remove($request_match) ;
        $em->flush();

        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
        ." ha borrado la solicitud de partido (id:".$request_match->getId().")");
        $notification->setType("remove");
        $notification->setEntity("requestMatch");
        $notification->setEnvironment("Backend");
        $notification->setUser($this->container->get('security.context')->getToken()->getUser());
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('request_match_index');

    }

    //---------------------FORMS------------------------------

    private function createCustomForm($id,$method,$route){
        return $this->createFormBuilder()->setAction($this->generateUrl($route, array ('id' => $id)))->setMethod($method)->getForm();
    }
}