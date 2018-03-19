<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\PlayerUser;
use BackendBundle\Entity\User;

class PlayerUserController extends Controller
{

    //---------------------EDIT------------------------------

    public function editAction(Request $request, User $user){

        $editForm = $this->createForm('BackendBundle\Form\PlayerUserType', $user->getPlayerUser());
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if(!empty($user->getPlayerUser()->fileIds)){
                $user->getPlayerUser()->setImgSrc("uploads/users/".$user->getPlayerUser()->fileIds);
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_index', array('id' => $user->getId()));
        }

        return $this->render('playerUser/edit.html.twig', array(
            'playerUser' => $user->getPlayerUser(),
            'edit_form' => $editForm->createView(),
        ));

    }

}