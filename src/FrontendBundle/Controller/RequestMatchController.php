<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;
use BackendBundle\Entity\RequestMatch;
use BackendBundle\Entity\Match;
use BackendBundle\Entity\Score;
use BackendBundle\Entity\UserMatch;

class RequestMatchController extends Controller
{

    public function getRequestsAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id_user = $request->get("id");
        
        $query = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT f FROM BackendBundle:RequestMatch f WHERE f.user_receive = :user AND f.status IS null'
        )->setParameter('user', $id_user );

        $matchs = $query->getResult();

        $arr = [];
        $arr1 = [];

        foreach($matchs as $f){

                $arr1['id'] = $f->getId();
                $arr1['id_user'] = $f->getUserSend()->getId();
                $arr1['username'] = $f->getUserSend()->getUsername();
                $arr1["path"] = $f->getUserSend()->getPlayerUser()->getImgSrc();
                $arr1['firstname'] = $f->getUserSend()->getPlayerUser()->getFirstname();
                $arr1['lastname'] = $f->getUserSend()->getPlayerUser()->getLastname();
                $arr1['gameLevel'] = $f->getUserSend()->getSkillUser()->getGameLevel();
                $arr1['gameStyle'] = $f->getUserSend()->getSkillUser()->getGameStyle();
                $arr1['dateText'] = $f->getDateMatch()->format('d-m-Y')." a las ".$f->getDateMatch()->format('h:i')." hs";
            
            $arr[] = $arr1;
        }

        return ResponseRest::returnOk($arr);
    }

    public function sendRequestMatchAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_send = $request->get("id_send");
        $user_send = $em->getRepository('BackendBundle:User')->findOneById($id_send);
        $id_receive = $request->get("id_receive");
        $user_receive = $em->getRepository('BackendBundle:User')->findOneById($id_receive);

        $title = $request->get("title");
        $date = $request->get("date");
        $hour = $request->get("hour");
        $googlePlaceId = $request->get("googlePlaceId");
        
        $request_match = new RequestMatch();
        $request_match->setUserSend($user_send);
        $request_match->setUserReceive($user_receive);
        $request_match->setTitle($title);
        $dateMatch = new \DateTime($date." ".$hour);
        $request_match->setDateMatch($dateMatch);
        $request_match->setGooglePlaceId($googlePlaceId);

        $em->persist($request_match);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }

    public function sendResponseMatchAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_request_match = $request->get("id");
        $status_request_match = $request->get("status");
        $request_match = $em->getRepository('BackendBundle:RequestMatch')->findOneById($id_request_match);
        
        if($status_request_match == 1){
            $request_match->setStatus(1);
            $match = new Match();
            $match->setTitle($request_match->getTitle());
            $match->setDateMatch($request_match->getDateMatch());
            $match->setGooglePlaceId($request_match->getGooglePlaceId());
            $match->setCreator($request_match->getUserSend());
            $match->setType("Singles");
            $match->setIsPrivate(0);
            $score = new Score();
            $score->setStatus(0);
            $match->setScore($score);
            $match->setStatus(0);
            $userMatch = new UserMatch();
            $userMatch->setUser($request_match->getUserSend());
            $userMatch->setUser2(null);
            $userMatch->setMatch($match);
            $userMatch_aux = new UserMatch();
            $userMatch_aux->setUser($request_match->getUserReceive());
            $userMatch_aux->setUser2(null);
            $userMatch_aux->setMatch($match);

            $em->persist($score);
            $em->persist($match);
            $em->persist($userMatch);
            $em->persist($userMatch_aux);
            $em->persist($request_match);
            }else{
                $em->remove($request_match);
        }
        $em->flush();

        return ResponseRest::returnOk("ok");

    }
    

}