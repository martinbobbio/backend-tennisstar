<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;

class MapController extends Controller
{
    public function getClubesAction(){
        
        header("Access-Control-Allow-Origin: *");

        $latitud = "-34.604486";
        $longitud = "-58.396329";

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



}