<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Match;
use BackendBundle\Entity\Score;
use BackendBundle\Entity\UserMatch;
use BackendBundle\Entity\Notification;
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

            if(isset($players[0])){
                if($players[0]->getUser() != null){
                    $arr1['player1AUsername'] = $players[0]->getUser()->getUsername();
                    $arr1['player1AId'] = $players[0]->getUser()->getId();
                    $arr1['player1APath'] = $players[0]->getUser()->getPlayerUser()->getImgSrc();
                }
                $arr1['id_um'] = $players[0]->getId();
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
        if($type == "Dobles"){
            $userMatch2 = new UserMatch();
            $userMatch2->setUser(null);
            $userMatch2->setUser2(null);
            $userMatch2->setMatch($match);
            $em->persist($userMatch2);
        }

        $em->persist($userMatch);
        $em->flush();
        
        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$creator->getUsername()
        ." ha creado un nuevo partido (id:".$match->getId().")");
        $notification->setType("add");
        $notification->setEntity("match");
        $notification->setEnvironment("Frontend");
        $notification->setUser($creator);
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

        return ResponseRest::returnOk("ok");

    }

    public function checkMatchAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_match = $request->get("id_um");
        $id_user = $request->get("id_user");

        $userMatch = $em->getRepository('BackendBundle:UserMatch')->findByMatch($id_match);

        if(isset($userMatch[0]) && isset($userMatch[1])){
            if($userMatch[1]->getUser() == null){
                $userMatch[1]->setUser($em->getRepository('BackendBundle:User')->findOneById($id_user));
                $em->persist($userMatch[1]);
                $em->flush();
                return ResponseRest::returnOk("ok");
            }
            if($userMatch[0]->getUser2() == null){
                $userMatch[0]->setUser2($em->getRepository('BackendBundle:User')->findOneById($id_user));
                $em->persist($userMatch[0]);
                $em->flush();
                return ResponseRest::returnOk("ok");
            }
            if($userMatch[1]->getUser2() == null){
                $userMatch[1]->setUser2($em->getRepository('BackendBundle:User')->findOneById($id_user));
                $em->persist($userMatch[1]);
                $em->flush();
                return ResponseRest::returnOk("ok");
            }
        }

        return ResponseRest::returnOk("error");

    }

    public function getMatchsAction(Request $request){

        header("Access-Control-Allow-Origin: *");
        $id_user = $request->get("id_user");

        $em = $this->getDoctrine()->getManager();

        $match = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT m FROM BackendBundle:UserMatch m
        WHERE m.user = :user OR m.user2 = :user'
        )->setParameter('user', $id_user)->getResult();

        $arr = [];
        $arr1 = [];

        foreach($match as $m){

            $arr1['player1AUsername'] = "";
            $arr1['player1AId'] = null;
            $arr1['player1APath'] = null;
            $arr1['player2AUsername'] = "";
            $arr1['player2AId'] = null;
            $arr1['player2APath'] = null;
            $arr1['player1BUsername'] = "";
            $arr1['player1BId'] = null;
            $arr1['player1BPath'] = null;
            $arr1['player2BUsername'] = "";
            $arr1['player2BId'] = null;
            $arr1['player2BPath'] = null;

            if($m->getMatch()->getType() == "Singles" && $m->getUser() != null){
                $match_aux = $this->getDoctrine()->getEntityManager()
                ->createQuery('SELECT m FROM BackendBundle:UserMatch m
                WHERE m.match = :match'
                )->setParameter('match', $m->getMatch()->getId())->getResult();
                if(sizeof($match_aux) == 2){
                    $arr1['dateText'] = $match_aux[0]->getMatch()->getDateMatch()->format('d-m-Y')." a las ".$match_aux[0]->getMatch()->getDateMatch()->format('h:i')." hs";
                    $arr1['title'] = $match_aux[0]->getMatch()->getTitle();
                    $arr1['type'] = $match_aux[0]->getMatch()->getType();
                    $arr1['idMatch'] = $match_aux[0]->getMatch()->getId();
                    $arr1['player1AUsername'] = $match_aux[0]->getUser()->getUsername();
                    $arr1['player1AId'] = $match_aux[0]->getUser()->getId();
                    $arr1['player1APath'] = $match_aux[0]->getUser()->getPlayerUser()->getImgSrc();
                    $arr1['player2AUsername'] = $match_aux[1]->getUser()->getUsername();
                    $arr1['player2AId'] = $match_aux[1]->getUser()->getId();
                    $arr1['player2APath'] = $match_aux[1]->getUser()->getPlayerUser()->getImgSrc();
                    $arr1['count'] = 2;
                    $arr[] = $arr1;
                }else if(sizeof($match_aux) == 1){
                    $arr1['dateText'] = $match_aux[0]->getMatch()->getDateMatch()->format('d-m-Y')." a las ".$match_aux[0]->getMatch()->getDateMatch()->format('h:i')." hs";
                    $arr1['title'] = $match_aux[0]->getMatch()->getTitle();
                    $arr1['type'] = $match_aux[0]->getMatch()->getType();
                    $arr1['idMatch'] = $match_aux[0]->getMatch()->getId();
                    $arr1['player1AUsername'] = $match_aux[0]->getUser()->getUsername();
                    $arr1['player1AId'] = $match_aux[0]->getUser()->getId();
                    $arr1['player1APath'] = $match_aux[0]->getUser()->getPlayerUser()->getImgSrc();
                    $arr1['count'] = 1;
                    $arr[] = $arr1;
                }

            }else if($m->getMatch()->getType() == "Dobles" && $m->getUser() != null){
                $match_aux = $this->getDoctrine()->getEntityManager()
                ->createQuery('SELECT m FROM BackendBundle:UserMatch m
                WHERE m.match = :match')->setParameter('match', $m->getMatch()->getId())->getResult();

                $count = 0;

                $arr1['dateText'] = $match_aux[0]->getMatch()->getDateMatch()->format('d-m-Y')." a las ".$match_aux[0]->getMatch()->getDateMatch()->format('h:i')." hs";
                $arr1['title'] = $match_aux[0]->getMatch()->getTitle();
                $arr1['type'] = $match_aux[0]->getMatch()->getType();
                $arr1['idMatch'] = $match_aux[0]->getMatch()->getId();
                
                if($match_aux[0]->getUser() != null && $match_aux[0]->getMatch()->getType() == "Dobles"){
                    $arr1['player1AUsername'] = $match_aux[0]->getUser()->getUsername();
                    $arr1['player1AId'] = $match_aux[0]->getUser()->getId();
                    $arr1['player1APath'] = $match_aux[0]->getUser()->getPlayerUser()->getImgSrc();
                    $count += 1;
                }
                if($match_aux[0]->getUser2() != null && $match_aux[0]->getMatch()->getType() == "Dobles"){
                    $arr1['player1BUsername'] = $match_aux[0]->getUser2()->getUsername();
                    $arr1['player1BId'] = $match_aux[0]->getUser2()->getId();
                    $arr1['player1BPath'] = $match_aux[0]->getUser2()->getPlayerUser()->getImgSrc();
                    $count += 1;
                }
                if($match_aux[1]->getUser() != null && $match_aux[1]->getMatch()->getType() == "Dobles"){
                    $arr1['player2AUsername'] = $match_aux[1]->getUser()->getUsername();
                    $arr1['player2AId'] = $match_aux[1]->getUser()->getId();
                    $arr1['player2APath'] = $match_aux[1]->getUser()->getPlayerUser()->getImgSrc();
                    $count += 1;
                }
                if($match_aux[1]->getUser2() != null && $match_aux[1]->getMatch()->getType() == "Dobles"){
                    $arr1['player2BUsername'] = $match_aux[1]->getUser2()->getUsername();
                    $arr1['player2BId'] = $match_aux[1]->getUser2()->getId();
                    $arr1['player2BPath'] = $match_aux[1]->getUser2()->getPlayerUser()->getImgSrc();
                    $count += 1;
                }

                $arr1['count'] = $count;
                $arr[] = $arr1;
        
            }
            
        }

        return ResponseRest::returnOk($arr);
        
    }


}