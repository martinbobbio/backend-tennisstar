<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;
use BackendBundle\Entity\User;
use BackendBundle\Entity\ClubFavorite;

class ClubFavoriteController extends Controller
{
    public function newAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id_user = $request->get("id_user");
        $id_google_place = $request->get("id_google_place");

        $em = $this->getDoctrine()->getManager();

        $club_favorite_delete = $em->getRepository('BackendBundle:ClubFavorite')->findOneByUser($id_user);

        if($club_favorite_delete != null){
            $em->remove($club_favorite_delete);
            $em->flush();
        }

        $user = $em->getRepository('BackendBundle:User')->findOneById($id_user);

        $clubFavorite = new ClubFavorite();

        $clubFavorite->setUser($user);
        $clubFavorite->setGooglePlaceId($id_google_place);

        $em->persist($clubFavorite);
        $em->flush();
        
        return ResponseRest::returnOk("ok");

    }

}