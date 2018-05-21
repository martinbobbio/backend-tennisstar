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

    public function getNotificationsByAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $type = $request->get("action");
        $environment = $request->get("environment");
        $entity = $request->get("entity");

        $notifications = [];

        if($type != "" && $environment != "" && $entity != ""){
            $notifications = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT n FROM BackendBundle:Notification n WHERE
            n.type = :type1 AND n.environment = :environment AND n.entity = :entity'
            )->setParameter('type1', $type )
            ->setParameter('environment', $environment )
            ->setParameter('entity', $entity )
            ->getResult();
        }else if($type != "" && $environment != ""){
            $notifications = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT n FROM BackendBundle:Notification n WHERE
            n.type = :type1 AND n.environment = :environment'
            )->setParameter('type1', $type )
            ->setParameter('environment', $environment )
            ->getResult();
        }else if($type != "" && $entity != ""){
            $notifications = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT n FROM BackendBundle:Notification n WHERE
            n.type = :type1 AND n.entity = :entity'
            )->setParameter('type1', $type )
            ->setParameter('entity', $entity )
            ->getResult();
        }else if($environment != "" && $entity != ""){
            $notifications = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT n FROM BackendBundle:Notification n WHERE
            n.environment = :environment AND n.entity = :entity'
            )->setParameter('environment', $environment )
            ->setParameter('entity', $entity )
            ->getResult();
        }else if($environment != ""){
            $notifications = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT n FROM BackendBundle:Notification n WHERE
            n.environment = :environment'
            )->setParameter('environment', $environment )
            ->getResult();
        }else if($type != ""){
            $notifications = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT n FROM BackendBundle:Notification n WHERE
            n.type = :type1'
            )->setParameter('type1', $type )
            ->getResult();
        }else if($entity != ""){
            $notifications = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT n FROM BackendBundle:Notification n WHERE
            n.entity = :entity'
            )->setParameter('entity', $entity )
            ->getResult();
        }


        $arr = [];
        $arr1 = [];

        foreach($notifications as $n){

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