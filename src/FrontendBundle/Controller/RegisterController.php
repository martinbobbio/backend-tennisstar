<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;

class RegisterController extends Controller
{
    public function newUserAction(Request $request){
        
        header("Access-Control-Allow-Origin: *");

        dump("xx");die;

        return ResponseRest::returnOk($request);

    }



}