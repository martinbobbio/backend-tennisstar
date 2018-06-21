<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\User;
use BackendBundle\Entity\PlayerUser;
use BackendBundle\Entity\SkillUser;
use BackendBundle\Entity\Notification;

class UserController extends Controller
{

    //---------------------INDEX------------------------------

    public function indexAction(Request $request){
        
        $con = $this->getDoctrine()->getManager();
        $userQuery = $con->getRepository('BackendBundle:User')->findAll();

        $delete_form = $this->createCustomForm(':ID','DELETE','user_delete');

        $paginator  = $this->get('knp_paginator');
        $user = $paginator->paginate(
          $userQuery,
          $request->query->getInt('page', 1),
          12);

        return $this->render('user/index.html.twig', array('user' => $user,'delete_form' => $delete_form->createView()));

    }

    //---------------------SHOW------------------------------

    public function showAction(Request $request, User $user){
        
        $delete_form = $this->createCustomForm(':ID','DELETE','user_delete');

        return $this->render('user/show.html.twig', array('user' => $user,'delete_form' => $delete_form->createView() ));

    }

    //---------------------EDIT------------------------------

    public function editAction(Request $request, User $user){
        
        $editForm = $this->createForm('BackendBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $user->setPassword($this->container->get('security.encoder_factory')->getEncoder($user)->encodePassword($user->getPassword(), $user->getSalt()));

            $this->getDoctrine()->getManager()->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha editado el usuario (id:".$user->getId().")");
            $notification->setType("edit");
            $notification->setEntity("user");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ));

    }

    //---------------------NEW------------------------------

    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('BackendBundle\Form\UserType', $user);
        $form->handleRequest($request);

        $userManager = $this->get('fos_user.user_manager');
        $email_exist = $userManager->findUserByEmail($user->getEmail());
        $username_exist = $userManager->findUserByUsername($user->getUsername());

        if ($form->isSubmitted() && $form->isValid() && !$email_exist && !$username_exist) {
            
            $em = $this->getDoctrine()->getManager();
            $user->setUsername1($user->getUsername());
            $user->setEmail($user->getEmail());
            $user->setEmailCanonical($user->getEmail());
            $user->setUsernameCanonical($user->getUsername());
            $user->setEnabled(1);
            $user->setPassword($this->container->get('security.encoder_factory')->getEncoder($user)->encodePassword($user->getPassword(), $user->getSalt()));

            $userPlayer = new PlayerUser();
            $user->setPlayerUser($userPlayer);
            
            $skillUser = new SkillUser();
            $user->setSkillUser($skillUser);

            $em->persist($user);
            $em->persist($userPlayer);
            $em->persist($skillUser);
            $em->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha agregado el usuario (id:".$user->getId().")");
            $notification->setType("add");
            $notification->setEntity("user");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    //---------------------DELETE------------------------------

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->find($id);

        $form = $this->createCustomForm($user->getId(),'DELETE', 'user_delete');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            if($request->isXMLHttpRequest()){
                
                $em->remove($user) ;
                $em->flush();   
                return new Response(json_encode(array('removed' => 1,'message' => 'Usuario borrado')),200, array('Content-Type' => 'application/json'));
            }

            $em->remove($user) ;
            $em->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha borrado el usuario (id:".$id.")");
            $notification->setType("delete");
            $notification->setEntity("user");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }
    }

    //---------------------FORMS------------------------------

    private function createCustomForm($id,$method,$route){
        return $this->createFormBuilder()->setAction($this->generateUrl($route, array ('id' => $id)))->setMethod($method)->getForm();
    }
}