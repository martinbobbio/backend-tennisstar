<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\ClubFavorite;
use BackendBundle\Entity\Notification;

class ClubFavoriteController extends Controller
{

    //---------------------INDEX------------------------------

    public function indexAction(){
        
        $con = $this->getDoctrine()->getManager();
        $club = $con->getRepository('BackendBundle:ClubFavorite')->findAll();

        $delete_form = $this->createCustomForm(':ID','DELETE','favorite_club_delete');

        return $this->render('clubfavorite/index.html.twig', array('club' => $club,'delete_form' => $delete_form->createView() ));

    }

    //---------------------DELETE------------------------------

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('BackendBundle:ClubFavorite')->find($id);

        $form = $this->createCustomForm($club->getId(),'DELETE', 'favorite_club_delete');
        $form->handleRequest($request);

        $em->remove($club);
        $em->flush();
        
        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
        ." ha borrado el club favorito (id:".$club->getId().")");
        $notification->setType("delete");
        $notification->setEntity("clubFavorite");
        $notification->setEnvironment("Backend");
        $notification->setUser($this->container->get('security.context')->getToken()->getUser());
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('favorite_club_index');
    }

    //---------------------FORMS------------------------------

    private function createCustomForm($id,$method,$route){
        return $this->createFormBuilder()->setAction($this->generateUrl($route, array ('id' => $id)))->setMethod($method)->getForm();
    }
}