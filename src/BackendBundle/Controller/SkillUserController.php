<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\SkillUser;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Notification;

class SkillUserController extends Controller
{

    //---------------------EDIT------------------------------

    public function editAction(Request $request, User $user){

        $editForm = $this->createForm('BackendBundle\Form\SkillUserType', $user->getSkillUser());
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha editado la informacion de juego (id:".$user->getSkillUser()->getId().")");
            $notification->setType("edit");
            $notification->setEntity("skillUser");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', array('id' => $user->getId()));
        }

        return $this->render('skillUser/edit.html.twig', array(
            'skillUser' => $user->getSkillUser(),
            'edit_form' => $editForm->createView(),
        ));

    }

}