<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function statsAction(Request $request)
    {
        return $this->render("home/stats.html.twig");
    }

    public function notificationAction(Request $request)
    {
        $con = $this->getDoctrine()->getManager();
        $notification = $con->getRepository('BackendBundle:Notification')->findAll();

        return $this->render('home/notification.html.twig', array('notification' => $notification));
    }

    public function redirectLoginAction(){

        if($this->container->get('security.context')->getToken()->getUser() == "anon."){
            return $this->redirect('/web/app_dev.php/login');
        }else{
            return $this->redirect('/web/app_dev.php/admin/home');
        }
        
    }
}
