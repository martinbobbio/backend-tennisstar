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

        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$latitud.",".$longitud."&radius=5000&name=tenis&key=AIzaSyAQZGWfnDR3C28jqGEiJqEQT4BvTXRy_bM";
        
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

        $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$id_google_place."&key=AIzaSyAQZGWfnDR3C28jqGEiJqEQT4BvTXRy_bM";
        
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



}