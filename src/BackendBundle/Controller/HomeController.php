<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function statsAction(Request $request)
    {
        $userCount = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT COUNT(u) as cantidad FROM BackendBundle:User u'
        )->getResult();
        $noticesCount = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT COUNT(n) as cantidad FROM BackendBundle:Notice n'
        )->getResult();
        $matchCount = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT COUNT(m) as cantidad FROM BackendBundle:Match m'
        )->getResult();
        $matchSinglesCount = $this->getDoctrine()->getEntityManager()
        ->createQuery("SELECT COUNT(m) as cantidad FROM BackendBundle:Match m where m.type = 'Singles'"
        )->getResult();
        $matchDoblesCount = $this->getDoctrine()->getEntityManager()
        ->createQuery("SELECT COUNT(m) as cantidad FROM BackendBundle:Match m where m.type = 'Dobles'"
        )->getResult();
        $tournamentCount = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT COUNT(t) as cantidad FROM BackendBundle:Tournament t'
        )->getResult();
        $clubFavoriteCount = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT COUNT(c) as cantidad FROM BackendBundle:ClubFavorite c'
        )->getResult();
        
        return $this->render("home/stats.html.twig", array(
            'userCount' => $userCount[0],
            'noticesCount' => $noticesCount[0],
            'matchCount' => $matchCount[0],
            'matchSinglesCount' => $matchSinglesCount[0],
            'matchDoblesCount' => $matchDoblesCount[0],
            'tournamentCount' => $tournamentCount[0],
            'clubFavoriteCount' => $clubFavoriteCount[0],
            ));
    }

    public function notificationAction(Request $request)
    {
        $con = $this->getDoctrine()->getManager();
        $notificationQuery = $con->getRepository('BackendBundle:Notification')->findAll();

        $paginator  = $this->get('knp_paginator');
        $notification = $paginator->paginate(
          $notificationQuery,
          $request->query->getInt('page', 1),
          25);

        return $this->render('home/notification.html.twig', array('notification' => $notification));
    }

    public function redirectLoginAction(){

        if($this->container->get('security.context')->getToken()->getUser() == "anon."){
            return $this->redirect('/admin/login');
        }else{
            return $this->redirect('/admin/stats');
        }
        
    }
}
