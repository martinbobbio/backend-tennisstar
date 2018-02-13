<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;

class LoginController extends Controller
{
    public function checkLoginAction(Request $request){
        
        header("Access-Control-Allow-Origin: *");

        $username = $request->get("email");
        $password = $request->get("password");
        
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
    
        $user = $user_manager->loadUserByUsername($username);
    
        $encoder = $factory->getEncoder($user);
    
        $bool = ($encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) ? "true" : "false";

        return ResponseRest::returnOk($request);

    }



}