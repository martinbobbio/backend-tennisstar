<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\PlayerUser;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Notification;

class PlayerUserController extends Controller
{

    //---------------------EDIT------------------------------

    public function editAction(Request $request, User $user){

        $editForm = $this->createForm('BackendBundle\Form\PlayerUserType', $user->getPlayerUser());
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if(!empty($user->getPlayerUser()->fileIds)){
                $user->getPlayerUser()->setImgSrc($user->getPlayerUser()->fileIds);
            }

            $this->getDoctrine()->getManager()->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha editado la informacion personal (id:".$user->getPlayerUser()->getId().")");
            $notification->setType("edit");
            $notification->setEntity("playerUser");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', array('id' => $user->getId()));
        }

        return $this->render('playerUser/edit.html.twig', array(
            'playerUser' => $user->getPlayerUser(),
            'edit_form' => $editForm->createView(),
        ));

    }

}