<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\SkillUser;
use BackendBundle\Entity\User;

class SkillUserController extends Controller
{

    //---------------------EDIT------------------------------

    public function editAction(Request $request, User $user){

        $editForm = $this->createForm('BackendBundle\Form\SkillUserType', $user->getSkillUser());
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_index', array('id' => $user->getId()));
        }

        return $this->render('skillUser/edit.html.twig', array(
            'skillUser' => $user->getSkillUser(),
            'edit_form' => $editForm->createView(),
        ));

    }

}