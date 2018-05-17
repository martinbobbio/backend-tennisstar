<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Notification;
use FrontendBundle\Entity\ResponseRest;

class NotificationController extends Controller
{
    public function getNotificationsAction(){
        
        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();
        $notification = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT n FROM BackendBundle:Notification n ORDER BY n.updateAt DESC'
        )->getResult();

        $arr = [];
        $arr1 = [];

        foreach($notification as $n){

            $arr1['user'] = $n->getUser()->getUsername();
            $arr1['title'] = $n->getTitle();
            $arr1['type'] = $n->getType();
            $arr1['entity'] = $n->getEntity();
            $arr1['environment'] = $n->getEnvironment();
            $arr1['date'] = $n->getCreateAt();
            $arr[] = $arr1;
        }

        return ResponseRest::returnOk($arr);

    }


}