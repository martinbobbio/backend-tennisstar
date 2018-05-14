<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Tournament;
use BackendBundle\Entity\Score;
use BackendBundle\Entity\UserTournament;
use FrontendBundle\Entity\ResponseRest;

class TournamentController extends Controller
{
    public function getTournamentRandomAction(){
        
        header("Access-Control-Allow-Origin: *");

        $tournament = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT t FROM BackendBundle:Tournament t 
        WHERE t.status = :status_aux'
        )->setParameter('status_aux', 0)->setMaxResults(3)
        ->getResult();

        $arr = [];
        $arr1 = [];

        foreach($tournament as $t){

            $players = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT ut FROM BackendBundle:UserTournament ut
            WHERE ut.tournament = :tournament'
            )->setParameter('tournament', $t->getId())
            ->getResult();
            
            $arr1['id'] = $t->getId();
            $arr1['title'] = $t->getTitle();
            $arr1['status'] = $t->getStatus();
            $arr1['date'] = $t->getDateTournament()->format('d/m/Y')." ".$t->getDateTournament()->format('h:i')."hs";
            $arr1['countTotal'] = $t->getCount();
            
            $countStatus = 0;
            foreach($players as $p){
                if($p->getTournament()->getCount() == 4 && $p->getInstance() == "Semifinal" && $p->getUser() != null){
                    $countStatus++;
                }
                if($p->getTournament()->getCount() == 8 && $p->getInstance() == "Cuartos de final" && $p->getUser() != null){
                    $countStatus++;
                }
                if($p->getTournament()->getCount() == 16 && $p->getInstance() == "Octavos de final" && $p->getUser() != null){
                    $countStatus++;
                }
            }
            $arr1['countStatus'] = $countStatus;

            $arr[] = $arr1;
        }
        return ResponseRest::returnOk($arr);

    }


    public function newTournamentAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_user = $request->get("id_user");
        $title = $request->get("title");
        $count = $request->get("count");
        $isPrivate = $request->get("isPrivate");
        $date = $request->get("date");
        $hour = $request->get("hour");
        $lon = $request->get("lon");
        $lat = $request->get("lat");
        $googlePlaceId = $request->get("googlePlaceId");

        $tournament = new Tournament();
        $tournament->setTitle($title);
        $tournament->setCount($count);
        $dateMatch = new \DateTime($date." ".$hour);
        $tournament->setDateTournament($tournament);
        $tournament->setLon($lat);
        $tournament->setLat($lon);
        $tournament->setGooglePlaceId($googlePlaceId);
        $creator = $em->getRepository('BackendBundle:User')->findOneById($id_user);
        $tournament->setCreator($creator);

        if($tournament->getCount() == 16){
            $this->createUserTournament("Octavos de final", 16, $tournament);
        }
        if($tournament->getCount() == 16 || $tournament->getCount() == 8){
            $this->createUserTournament("Cuartos de final", 8, $tournament);
        }
        if($tournament->getCount() == 16 || $tournament->getCount() == 8 || $tournament->getCount() == 4){
            $this->createUserTournament("Semifinal", 4, $tournament);
        }

        $this->createUserTournament("Final", 2, $tournament);
        
        $em->persist($tournament);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }

    public function getTournamentAction(Request $request){

        $id = $request->get("id");
        
        $em = $this->getDoctrine()->getManager();
        $t = $em->getRepository('BackendBundle:Tournament')->findOneById($id);

        $players = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT ut FROM BackendBundle:UserTournament ut
        WHERE ut.tournament = :tournament'
        )->setParameter('tournament', $t->getId())
        ->getResult();

        $arr = [];
        $arr1 = [];
        
        $arr['id'] = $t->getId();
        $arr['title'] = $t->getTitle();
        $arr['status'] = $t->getStatus();
        $arr['date'] = $t->getDateTournament()->format('d/m/Y')." ".$t->getDateTournament()->format('h:i')."hs";
        $arr['countTotal'] = $t->getCount();
        $arr['googlePlaceId'] = $t->getGooglePlaceId();
        
        $countStatus = 0;
        foreach($players as $p){
            if($p->getTournament()->getCount() == 4 && $p->getInstance() == "Semifinal" && $p->getUser() != null){
                $countStatus++;
            }
            if($p->getTournament()->getCount() == 8 && $p->getInstance() == "Cuartos de final" && $p->getUser() != null){
                $countStatus++;
            }
            if($p->getTournament()->getCount() == 16 && $p->getInstance() == "Octavos de final" && $p->getUser() != null){
                $countStatus++;
            }
        }
        $arr['countStatus'] = $countStatus;

        $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$t->getGooglePlaceId()."&key=AIzaSyAQZGWfnDR3C28jqGEiJqEQT4BvTXRy_bM";
        $arrContextOptions=array(
            "ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false));
        $json = file_get_contents($url, false, stream_context_create($arrContextOptions));
        $json_data = json_decode($json, true);
        $arr['clubTitle'] = $json_data["result"]["name"];

        return ResponseRest::returnOk($arr);

    }

    private function createUserTournament($instance, $count, $tournament){

        $em = $this->getDoctrine()->getManager();

        for($i = 0;$i < $count ;$i++){
            $userTournament = new UserTournament();
            $userTournament->setTournament($tournament);
            $userTournament->setUser(null);
            $userTournament->setScore(null);    
            $userTournament->setInstance($instance);
            $em->persist($userTournament);
        }
    }

}