<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Tournament;
use BackendBundle\Entity\Score;
use BackendBundle\Entity\UserTournament;
use BackendBundle\Entity\Notification;
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
    
    public function getTournamentsAction(){
        
        header("Access-Control-Allow-Origin: *");

        $tournament = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT t FROM BackendBundle:Tournament t 
        WHERE t.status = :status_aux AND t.google_place_id IS NOT NULL AND t.lat IS NOT NULL AND t.lon IS NOT NULL'
        )->setParameter('status_aux', 0)
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
            $arr1['date'] = $t->getDateTournament()->format('d/m/Y')." ".$t->getDateTournament()->format('h:i')."hs";
            $arr1['countTotal'] = $t->getCount();
            $arr1['googlePlaceId'] = $t->getGooglePlaceId();
            $arr1['creator'] = $t->getCreator()->getUsername();
            $arr1['lat'] = $t->getLat();
            $arr1['lon'] = $t->getLon();
            
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
        $date = $request->get("date");
        $hour = $request->get("hour");
        $lon = $request->get("lon");
        $lat = $request->get("lat");
        $googlePlaceId = $request->get("googlePlaceId");

        $tournament = new Tournament();
        $tournament->setTitle($title);
        $tournament->setCount($count);
        $dateTournament = new \DateTime($date." ".$hour);
        $tournament->setDateTournament($dateTournament);
        $tournament->setLon($lat);
        $tournament->setLat($lon);
        $tournament->setGooglePlaceId($googlePlaceId);
        $creator = $em->getRepository('BackendBundle:User')->findOneById($id_user);
        $tournament->setCreator($creator);
        $tournament->setStatus(0);

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
        
        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$creator->getUsername()
        ." ha creado un nuevo torneo(id:".$tournament->getId().")");
        $notification->setType("add");
        $notification->setEntity("tournament");
        $notification->setEnvironment("Frontend");
        $notification->setUser($creator);
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

        return ResponseRest::returnOk("ok");

    }

    public function getTournamentAction(Request $request){

        $id_user = $request->get("id_user");
        $id = $request->get("id_tournament");
        
        $em = $this->getDoctrine()->getManager();
        $t = $em->getRepository('BackendBundle:Tournament')->findOneById($id);
        $user = $em->getRepository('BackendBundle:User')->findOneById($id_user);

        $players = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT ut FROM BackendBundle:UserTournament ut
        WHERE ut.tournament = :tournament'
        )->setParameter('tournament', $t->getId())
        ->getResult();

        $arr = [];
        
        $arr['id'] = $t->getId();
        $arr['id_creator'] = $t->getCreator()->getId();
        $arr['title'] = $t->getTitle();
        $arr['status'] = $t->getStatus();
        $arr['date'] = $t->getDateTournament()->format('d/m/Y')." ".$t->getDateTournament()->format('h:i')."hs";
        $arr['countTotal'] = $t->getCount();
        $arr['googlePlaceId'] = $t->getGooglePlaceId();
        $arr['inscription'] = false;
        $arr['inscriptionFull'] = false;
        
        $countStatus = 0;
        $semifinal = [];
        $final = [];
        foreach($players as $p){
            if($p->getTournament()->getCount() == 4 && $p->getInstance() == "Semifinal" && $p->getUser() != null){
                $countStatus++;
                if($p->getUser() == $user){
                    $arr['inscription'] = true;
                }
                if($countStatus == 4){
                    $arr['inscriptionFull'] = true;
                }
            }
            if($p->getTournament()->getCount() == 8 && $p->getInstance() == "Cuartos de final" && $p->getUser() != null){
                $countStatus++;
                if($p->getUser() == $user){
                    $arr['inscription'] = true;
                }
                if($countStatus == 8){
                    $arr['inscriptionFull'] = true;
                }
            }
            if($p->getTournament()->getCount() == 16 && $p->getInstance() == "Octavos de final" && $p->getUser() != null){
                $countStatus++;
                if($p->getUser() == $user){
                    $arr['inscription'] = true;
                }
                if($countStatus == 16){
                    $arr['inscriptionFull'] = true;
                }
            }
            
            $arr1 = [];
            
            if($p->getInstance() == "Final"){
                if($p->getUser() != null){
                $arr1["id"] = $p->getId();
                $arr1["id_user"] = $p->getUser()->getId();
                $arr1["username"] = $p->getUser()->getUsername();
                $arr1["win"] = $p->getWin();
                }
                $final[] = $arr1;
            }
            if($p->getInstance() == "Semifinal"){
                if($p->getUser() != null){
                $arr1["id"] = $p->getId();
                $arr1["id_user"] = $p->getUser()->getId();
                $arr1["username"] = $p->getUser()->getUsername();
                $arr1["win"] = $p->getWin();
                }
                $semifinal[] = $arr1;
            }
            
        }
        $arr['matchs']["semifinal"] = $semifinal;
        $arr['matchs']["final"] = $final;
        $arr['countStatus'] = $countStatus;

        $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$t->getGooglePlaceId()."&key=AIzaSyAhPtTAhzM__YAfQI1Cyg_QY6Ox-lF8Yks";
        $arrContextOptions=array(
            "ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false));
        $json = file_get_contents($url, false, stream_context_create($arrContextOptions));
        $json_data = json_decode($json, true);
        $arr['clubTitle'] = $json_data["result"]["name"];

        return ResponseRest::returnOk($arr);

    }
    
    public function getMyTournamentsAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_user = $request->get("id_user");
        $creator = $em->getRepository('BackendBundle:User')->findOneById($id_user);
        
        $tournaments = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT t FROM BackendBundle:Tournament t
        WHERE t.creator = :creator AND t.lat IS NOT NULL AND t.lon IS NOT NULL AND t.google_place_id IS NOT NULL'
        )->setParameter('creator', $creator)
        ->getResult();
        
        $arr = [];
        $arr1 = [];
        
        foreach($tournaments as $t){
            
            $arr1['id'] = $t->getId();
            $arr1['title'] = $t->getTitle();
            $arr1['status'] = $t->getStatus();
            $arr1['date'] = $t->getDateTournament()->format('d/m/Y')." ".$t->getDateTournament()->format('h:i')."hs";
            $arr1['countTotal'] = $t->getCount();
            $arr1['googlePlaceId'] = $t->getGooglePlaceId();
            
            $players = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT ut FROM BackendBundle:UserTournament ut
            WHERE ut.tournament = :tournament'
            )->setParameter('tournament', $t->getId())
            ->getResult();
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
    
            $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$t->getGooglePlaceId()."&key=AIzaSyAhPtTAhzM__YAfQI1Cyg_QY6Ox-lF8Yks";
            $arrContextOptions=array(
                "ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false));
            $json = file_get_contents($url, false, stream_context_create($arrContextOptions));
            $json_data = json_decode($json, true);
            $arr1['clubTitle'] = $json_data["result"]["name"];
            
            $arr[] = $arr1;
            }
            
        return ResponseRest::returnOk($arr);  

        
    }
        
    
    public function inscriptionAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_user = $request->get("id_user");
        $id_tournament = $request->get("id_tournament");
        $count = $request->get("count");
        
        $instance = "";
        if($count == 4){
            $instance = "Semifinal";
        }else if($count == 8){
            $instance = "Cuartos de final";
        }else if($count == 16){
            $instance = "Octavos de final";
        }
        
        $user = $em->getRepository('BackendBundle:User')->findOneById($id_user);
        $tournament = $em->getRepository('BackendBundle:User')->findOneById($id_tournament);
        
        $players = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT ut FROM BackendBundle:UserTournament ut
        WHERE ut.tournament = :tournament and ut.instance = :instance'
        )->setParameter('tournament', $tournament)->setParameter('instance', $instance)
        ->getResult();
        
        foreach($players as $p){
            
            if($p->getUser() == $user){
                break;
            }
            
            if($p->getUser() == null){
                $p->setUser($user);
                $em->persist($p);
                $em->flush();
                
                $notification = new Notification();
                $notification->setType("add");
                $notification->setEntity("tournament");
                $notification->setEnvironment("Frontend");
                $notification->setUser($user);
                $notification->setTitle("El usuario ".$user->getUsername()." se ha inscrito en el torneo(id:".$tournament->getId().")");
                $this->getDoctrine()->getManager()->persist($notification);
                $this->getDoctrine()->getManager()->flush();
                
                return ResponseRest::returnOk("ok");
            }
            
        }
        
        return ResponseRest::returnOk("error"); 
        
    }
    
    
    public function uploadScoreAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_user_1 = $request->get("id_user_1");
        $id_user_2 = $request->get("id_user_2");
        $id_tournament_1 = $request->get("id_tournament_1");
        $id_tournament_2 = $request->get("id_tournament_2");
        $id_tournament = $request->get("id_tournament");

        $set1a = $request->get("set1a");
        $set1b = $request->get("set1b");
        $set1c = $request->get("set1c");
        $set2a = $request->get("set2a");
        $set2b = $request->get("set2b");
        $set2c = $request->get("set2c");
        
        $win = $request->get("win");
        $instance = $request->get("instance");
        $position = $request->get("position");

        $scoreWin = new Score();
        $scoreLoss = new Score();

        $scoreWin->setFirstSetJ1($set1a);
        $scoreWin->setFirstSetJ2($set2a);
        $scoreWin->setSecondSetJ1($set1b);
        $scoreWin->setSecondSetJ2($set2b);
        $scoreWin->setThirdSetJ1($set1c);
        $scoreWin->setThirdSetJ2($set2c);
        $scoreLoss->setFirstSetJ1($set2a);
        $scoreLoss->setFirstSetJ2($set1a);
        $scoreLoss->setSecondSetJ1($set2b);
        $scoreLoss->setSecondSetJ2($set1b);
        $scoreLoss->setThirdSetJ1($set2c);
        $scoreLoss->setThirdSetJ2($set1c);

        $em->persist($scoreWin);
        $em->persist($scoreLoss);

        $user1 = $em->getRepository('BackendBundle:User')->findOneById($id_user_1);
        $user2 = $em->getRepository('BackendBundle:User')->findOneById($id_user_2);

        $user_tournament_1 = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT m FROM BackendBundle:UserTournament m
        WHERE m.id = :ut'
        )->setParameter('ut', $id_tournament_1)->getResult()[0];
        
        $user_tournament_2 = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT m FROM BackendBundle:UserTournament m
        WHERE m.id = :ut'
        )->setParameter('ut', $id_tournament_2)->getResult()[0];

        if($win){
            $user_tournament_1->setScore($scoreWin);
            $user_tournament_2->setScore($scoreLoss);
            $user_tournament_1->setWin(1);
            $user_tournament_2->setWin(0);
        }else{
            $user_tournament_2->setScore($scoreWin);
            $user_tournament_1->setScore($scoreLoss);
            $user_tournament_1->setWin(0);
            $user_tournament_2->setWin(1);
        }
        
        if($instance == "Semifinal"){
            $nextDraw = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT m FROM BackendBundle:UserTournament m
            WHERE m.tournament = :tournament AND m.instance = :instance'
            )->setParameter('tournament', $id_tournament)->setParameter('instance', "Final")->getResult();

            if($position == 1){
                if($win){
                    $nextDraw[0]->setUser($user1);
                }else{
                    $nextDraw[0]->setUser($user2);
                }
                $em->persist($nextDraw[0]);
                $em->flush();
            }else if($position == 2){
                if($win){
                    $nextDraw[1]->setUser($user1);
                }else{
                    $nextDraw[1]->setUser($user2);
                }
                $em->persist($nextDraw[1]);
                $em->flush();
            }
        }
        
        $em->persist($user_tournament_1);
        $em->persist($user_tournament_2);
        
        $em->flush();

        return ResponseRest::returnOk("ok");

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