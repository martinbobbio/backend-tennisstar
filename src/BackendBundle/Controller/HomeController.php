<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render("home/index.html.twig");
    }

    public function redirectLoginAction(){

        if($this->container->get('security.context')->getToken()->getUser() == "anon."){
            return $this->redirect('/web/app_dev.php/login');
        }else{
            return $this->redirect('/web/app_dev.php/admin/home');
        }
        
    }
}
