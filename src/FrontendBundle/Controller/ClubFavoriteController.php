<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Notification;
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
        
        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$user->getUsername()
        ." ha añadido un nuevo club (id:".$clubFavorite->getId().")");
        $notification->setType("add");
        $notification->setEntity("clubFavorite");
        $notification->setEnvironment("Frontend");
        $notification->setUser($user);
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();
        
        return ResponseRest::returnOk("ok");

    }


    public function getMostClubesAction(){

        header("Access-Control-Allow-Origin: *");

        $clubs = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT COUNT(cf) as cantidad, cf.google_place_id
        FROM BackendBundle:ClubFavorite cf GROUP BY cf.google_place_id'
        )->setMaxResults(3)->getResult();

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $arr = [];
        $arr1 = [];

        foreach ($clubs as $c) {

            $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$c["google_place_id"]."&key=AIzaSyAQZGWfnDR3C28jqGEiJqEQT4BvTXRy_bM";
            $json = file_get_contents($url, false, stream_context_create($arrContextOptions));
            $json_data = json_decode($json, true);

            $arr1['cantidad'] = $c["cantidad"];
            $arr1['key'] = $c["google_place_id"];
            $arr1['title'] = $json_data["result"]["name"];
            if(isset($json_data["result"]["rating"])){
                $arr1['rating'] = $json_data["result"]["rating"];
            }else{
                $arr1['rating'] = null;
            }
            if(isset($json_data["result"]["photos"])){
                $arr1['photo'] = $json_data["result"]["photos"][0]["photo_reference"];
            }else{
                $arr1['photo'] = null;
            }
            $arr[] = $arr1;

        }
        
        return ResponseRest::returnOk($arr);

    }

}