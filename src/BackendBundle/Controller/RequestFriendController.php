<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\RequestFriend;

class RequestFriendController extends Controller
{

    //---------------------INDEX------------------------------

    public function indexAction(){
        
        $con = $this->getDoctrine()->getManager();
        $request_friend = $con->getRepository('BackendBundle:RequestFriend')->findAll();

        $delete_form = $this->createCustomForm(':ID','DELETE','request_friend_delete');

        return $this->render('requestfriend/index.html.twig', array('request_friend' => $request_friend,'delete_form' => $delete_form->createView() ));

    }

    //---------------------EDIT------------------------------

    public function editAction(Request $request, RequestFriend $request_friend){
        
        $editForm = $this->createForm('BackendBundle\Form\RequestFriendType', $request_friend);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('request_friend_index', array('id' => $request_friend->getId()));
        }

        return $this->render('requestfriend/edit.html.twig', array(
            'request_friend' => $request_friend,
            'edit_form' => $editForm->createView(),
        ));

    }

    //---------------------NEW------------------------------

    public function newAction(Request $request)
    {
        $request_friend = new RequestFriend();
        $form = $this->createForm('BackendBundle\Form\RequestFriendType', $request_friend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($request_friend);
            $em->flush();

            return $this->redirectToRoute('request_friend_index');
        }

        return $this->render('requestfriend/new.html.twig', array(
            'request_friend' => $request_friend,
            'form' => $form->createView(),
        ));
    }

    //---------------------DELETE------------------------------

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $request_friend = $em->getRepository('BackendBundle:RequestFriend')->find($id);

        $form = $this->createCustomForm($request_friend->getId(),'DELETE', 'request_friend_delete');
        $form->handleRequest($request);

        $em->remove($request_friend) ;
        $em->flush();

        return $this->redirectToRoute('request_friend_index');

    }

    //---------------------FORMS------------------------------

    private function createCustomForm($id,$method,$route){
        return $this->createFormBuilder()->setAction($this->generateUrl($route, array ('id' => $id)))->setMethod($method)->getForm();
    }
}