<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Match;
use BackendBundle\Entity\Score;
use BackendBundle\Entity\UserMatch;
use FrontendBundle\Entity\ResponseRest;

class MatchController extends Controller
{
    public function getMatchRandomAction(){
        
        header("Access-Control-Allow-Origin: *");

        $match = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT m FROM BackendBundle:Match m 
        WHERE m.status = :status_aux'
        )->setParameter('status_aux', 0)->setMaxResults(3)
        ->getResult();

        $arr = [];
        $arr1 = [];

        foreach($match as $m){

            $players = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT um FROM BackendBundle:UserMatch um
            WHERE um.match = :match'
            )->setParameter('match', $m->getId())
            ->getResult();
            
            $arr1['id'] = $m->getId();
            $arr1['title'] = $m->getTitle();
            $arr1['dateMatch'] = $m->getDateMatch();
            $arr1['type'] = $m->getType();
            $arr1['isPrivate'] = $m->getIsPrivate();
            $arr1['creator'] = $m->getCreator()->getUsername();
            $arr1['player1AUsername'] = null; $arr1['player1AId'] = null; $arr1['player1APath'] = null;
            $arr1['player1BUsername'] = null; $arr1['player1BId'] = null; $arr1['player1BPath'] = null;
            $arr1['player2AUsername'] = null; $arr1['player2AId'] = null; $arr1['player2APath'] = null;
            $arr1['player2BUsername'] = null; $arr1['player2BId'] = null; $arr1['player2BPath'] = null;

            if(isset($players[0]))
                if($players[0]->getUser() != null){
                    $arr1['player1AUsername'] = $players[0]->getUser()->getUsername();
                    $arr1['player1AId'] = $players[0]->getUser()->getId();
                    $arr1['player1APath'] = $players[0]->getUser()->getPlayerUser()->getImgSrc();
                }
            if(isset($players[0]))
                if($players[0]->getUser2() != null){
                    $arr1['player1BUsername'] = $players[0]->getUser2()->getUsername();
                    $arr1['player1BId'] = $players[0]->getUser2()->getId();
                    $arr1['player1BPath'] = $players[0]->getUser2()->getPlayerUser()->getImgSrc();
                }
            if(isset($players[1]))
                if($players[1]->getUser() != null){
                    $arr1['player2AUsername'] = $players[1]->getUser()->getUsername();
                    $arr1['player2AId'] = $players[1]->getUser()->getId();
                    $arr1['player2APath'] = $players[1]->getUser()->getPlayerUser()->getImgSrc();
                }
            if(isset($players[1]))
                if($players[1]->getUser2() != null){
                    $arr1['player2BUsername'] = $players[1]->getUser2()->getUsername();
                    $arr1['player2BId'] = $players[1]->getUser2()->getId();
                    $arr1['player2BPath'] = $players[1]->getUser2()->getPlayerUser()->getImgSrc();
                }
            
            $arr[] = $arr1;
        }
        return ResponseRest::returnOk($arr);

    }


    public function newMatchAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_user = $request->get("id_user");
        $title = $request->get("title");
        $type = $request->get("type");
        $isPrivate = $request->get("isPrivate");
        $date = $request->get("date");
        $hour = $request->get("hour");
        $lon = $request->get("lon");
        $lat = $request->get("lat");
        $googlePlaceId = $request->get("googlePlaceId");

        $match = new Match();
        $match->setTitle($title);
        $match->setType($type);
        $match->setIsPrivate($isPrivate);
        $dateMatch = new \DateTime($date." ".$hour);
        $match->setDateMatch($dateMatch);
        $match->setLon($lat);
        $match->setLat($lon);
        $match->setGooglePlaceId($googlePlaceId);
        $creator = $em->getRepository('BackendBundle:User')->findOneById($id_user);
        $match->setCreator($creator);
        $score = new Score();
        $score->setStatus(0);
        $match->setScore($score);
        $match->setStatus(0);
        
        $em->persist($match);
        $em->persist($score);
        $em->flush();

        $userMatch = new UserMatch();
        $userMatch->setUser($creator);
        $userMatch->setUser2(null);
        $userMatch->setMatch($match);

        $em->persist($userMatch);
        $em->flush();

        //dump($match->getId());die;

        

        return ResponseRest::returnOk("ok");




    }


}