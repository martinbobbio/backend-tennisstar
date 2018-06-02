<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;

class MapController extends Controller
{
    public function getClubesAction(Request $request){
        
        header("Access-Control-Allow-Origin: *");

        $latitud = $request->get("lat");
        $longitud = $request->get("lon");

        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$latitud.",".$longitud."&radius=5000&name=tenis&key=AIzaSyAhPtTAhzM__YAfQI1Cyg_QY6Ox-lF8Yks";
        
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        
        $json = file_get_contents($url, false, stream_context_create($arrContextOptions));

        $json_data = json_decode($json, true);

        return ResponseRest::returnOk($json_data);

    }

    public function getClubAction(Request $request){
     
        header("Access-Control-Allow-Origin: *");

        $id_google_place = $request->get("id_google_place");

        $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$id_google_place."&key=AIzaSyAhPtTAhzM__YAfQI1Cyg_QY6Ox-lF8Yks";
        
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        
        $json = file_get_contents($url, false, stream_context_create($arrContextOptions));
        $json_data = json_decode($json, true);

        $matchs = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT m FROM BackendBundle:Match m
        WHERE m.google_place_id = :googleplaceid AND m.status = 1'
        )->setParameter('googleplaceid', $id_google_place)->getResult();

        $arr = [];
        $arr1 = [];

        foreach($matchs as $m){

            $arr1['title'] = $m->getTitle();
            $arr1['type'] = $m->getType();

            $um = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT m FROM BackendBundle:UserMatch m
            WHERE m.match = :id_match'
            )->setParameter('id_match', $m->getId())->getResult();

            if($m->getType() == "Singles"){
                $arr1["p1a"] = $um[0]->getUser()->getUsername();
                $arr1["p2a"] = $um[1]->getUser()->getUsername();
                $arr1["p1win"] = $um[0]->getWin();
                $arr1["p1score"] = $um[0]->getScore()->getScoreString();
            }else if($m->getType() == "Dobles"){
                $arr1["p1a"] = $um[0]->getUser()->getUsername();
                $arr1["p2a"] = $um[1]->getUser()->getUsername();
                $arr1["p1b"] = $um[0]->getUser2()->getUsername();
                $arr1["p2b"] = $um[1]->getUser2()->getUsername();
                $arr1["p1win"] = $um[0]->getWin();
                $arr1["p1score"] = $um[0]->getScore()->getScoreString();
            }

            $arr[] = $arr1;
        }
        $json_data["matchs"] = $arr;

        $tournaments = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT m FROM BackendBundle:Tournament m
        WHERE m.google_place_id = :googleplaceid AND m.status = 1'
        )->setParameter('googleplaceid', $id_google_place)->getResult();

        $arr = [];
        $arr1 = [];

        foreach($tournaments as $m){
            $arr1['id'] = $m->getId();
            $arr1['title'] = $m->getTitle();
            $arr1['count'] = $m->getCount();
            $arr1['creator'] = $m->getCreator()->getUsername();
            $arr1['creator_id'] = $m->getCreator()->getId();
            $arr1['count'] = $m->getCount();

            $arr[] = $arr1;
        }
        $json_data["tournaments"] = $arr;


        
        return ResponseRest::returnOk($json_data);

    }



}