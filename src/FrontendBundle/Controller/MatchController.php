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
    public function getMatchRandomAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $id_user = $request->get("id_user");
        $user = $em->getRepository('BackendBundle:User')->findOneById($id_user);

        $match = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT m FROM BackendBundle:Match m 
        WHERE m.status = :status_aux'
        )->setParameter('status_aux', 0)->setMaxResults(20)
        ->getResult();

        $arr = [];
        $arr1 = [];
        
        $limit = 0;

        foreach($match as $m){
            
            if($limit == 3){
                break;
            }
            
            $exist = false;

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
            $arr1['date'] = $m->getDateMatch()->format('d/m/Y')." ".$m->getDateMatch()->format('h:i')."hs";
            $arr1['player1AUsername'] = null; $arr1['player1AId'] = null; $arr1['player1APath'] = null;
            $arr1['player1BUsername'] = null; $arr1['player1BId'] = null; $arr1['player1BPath'] = null;
            $arr1['player2AUsername'] = null; $arr1['player2AId'] = null; $arr1['player2APath'] = null;
            $arr1['player2BUsername'] = null; $arr1['player2BId'] = null; $arr1['player2BPath'] = null;

            if(isset($players[0])){
                if($players[0]->getUser() != null){
                    $arr1['player1AUsername'] = $players[0]->getUser()->getUsername();
                    $arr1['player1AId'] = $players[0]->getUser()->getId();
                    $arr1['player1APath'] = $players[0]->getUser()->getPlayerUser()->getImgSrc();
                    if($players[0]->getUser() == $user){
                        $exist = true;
                    }
                }
                $arr1['id_um'] = $players[0]->getId();
            }
            if(isset($players[0]))
                if($players[0]->getUser2() != null){
                    $arr1['player1BUsername'] = $players[0]->getUser2()->getUsername();
                    $arr1['player1BId'] = $players[0]->getUser2()->getId();
                    $arr1['player1BPath'] = $players[0]->getUser2()->getPlayerUser()->getImgSrc();
                    if($players[0]->getUser2() == $user){
                        $exist = true;
                    }
                }
            if(isset($players[1]))
                if($players[1]->getUser() != null){
                    $arr1['player2AUsername'] = $players[1]->getUser()->getUsername();
                    $arr1['player2AId'] = $players[1]->getUser()->getId();
                    $arr1['player2APath'] = $players[1]->getUser()->getPlayerUser()->getImgSrc();
                    if($players[1]->getUser() == $user){
                        $exist = true;
                    }
                }
            if(isset($players[1]))
                if($players[1]->getUser2() != null){
                    $arr1['player2BUsername'] = $players[1]->getUser2()->getUsername();
                    $arr1['player2BId'] = $players[1]->getUser2()->getId();
                    $arr1['player2BPath'] = $players[1]->getUser2()->getPlayerUser()->getImgSrc();
                    if($players[1]->getUser2() == $user){
                        $exist = true;
                    }
                }
                
            if(!$exist){
                $arr[] = $arr1;
                $limit++;
            }
            
            
                
            
            
        }
        return ResponseRest::returnOk($arr);

    }
    
    
    public function getAllMatchsAction(){
        
        header("Access-Control-Allow-Origin: *");

        $match = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT m FROM BackendBundle:Match m 
        WHERE m.status = :status_aux AND m.google_place_id IS NOT NULL AND m.lat IS NOT NULL AND m.lon IS NOT NULL'
        )->setParameter('status_aux', 0)
        ->getResult();

        $arr = [];
        $arr1 = [];

        foreach($match as $m){

            $players = $this->getDoctrine()->getEntityManager()
            ->createQuery('SELECT um FROM BackendBundle:UserMatch um
            WHERE um.match = :match AND um.finish IS NULL'
            )->setParameter('match', $m->getId())
            ->getResult();
            
            $arr1['title'] = $m->getTitle();
            $arr1['dateMatch'] = $m->getDateMatch();
            $arr1['type'] = $m->getType();
            $arr1['isPrivate'] = $m->getIsPrivate();
            $arr1['googlePlaceId'] = $m->getGooglePlaceId();
            $arr1['lat'] = $m->getLat();
            $arr1['lon'] = $m->getLon();
            $arr1['creator'] = $m->getCreator()->getUsername();
            $arr1['date'] = $m->getDateMatch()->format('d/m/Y')." ".$m->getDateMatch()->format('h:i')."hs";
            $arr1['player1AUsername'] = null; $arr1['player1AId'] = null;
            $arr1['player1BUsername'] = null; $arr1['player1BId'] = null;
            $arr1['player2AUsername'] = null; $arr1['player2AId'] = null;
            $arr1['player2BUsername'] = null; $arr1['player2BId'] = null;

            if(isset($players[0])){
                if($players[0]->getUser() != null){
                    $arr1['player1AUsername'] = $players[0]->getUser()->getUsername();
                    $arr1['player1AId'] = $players[0]->getUser()->getId();
                }
                $arr1['id_um'] = $players[0]->getId();
                $arr1['id_m'] = $players[0]->getMatch()->getId();
            }
            if(isset($players[0]))
                if($players[0]->getUser2() != null){
                    $arr1['player1BUsername'] = $players[0]->getUser2()->getUsername();
                    $arr1['player1BId'] = $players[0]->getUser2()->getId();
                }
            if(isset($players[1]))
                if($players[1]->getUser() != null){
                    $arr1['player2AUsername'] = $players[1]->getUser()->getUsername();
                    $arr1['player2AId'] = $players[1]->getUser()->getId();
                }
            if(isset($players[1]))
                if($players[1]->getUser2() != null){
                    $arr1['player2BUsername'] = $players[1]->getUser2()->getUsername();
                    $arr1['player2BId'] = $players[1]->getUser2()->getId();
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
            if($userMatch[0]->getUser() == null){
                $userMatch[0]->setUser($em->getRepository('BackendBundle:User')->findOneById($id_user));
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
        if(isset($userMatch[0])){
            $userMatchNew = new UserMatch();
            $userMatchNew->setUser($em->getRepository('BackendBundle:User')->findOneById($id_user));
            $userMatchNew->setUser2(null);
            $userMatchNew->setMatch($userMatch[0]->getMatch());
            $em->persist($userMatchNew);
            $em->flush();
            return ResponseRest::returnOk("ok");
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

            if($m->getMatch()->getStatus() !== 1){

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

            }else if($m->getMatch()->getType() == "Dobles"){
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
            
        }

        return ResponseRest::returnOk($arr);
        
    }


    public function uploadScoreAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_user = $request->get("id_user");
        $id_match = $request->get("id_match");
        $set1a = $request->get("set1a");
        $set1b = $request->get("set1b");
        $set1c = $request->get("set1c");
        $set2a = $request->get("set2a");
        $set2b = $request->get("set2b");
        $set2c = $request->get("set2c");
        $win = $request->get("win");

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

        $user = $em->getRepository('BackendBundle:User')->findOneById($id_user);

        $user_match = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT m FROM BackendBundle:UserMatch m
        WHERE m.match = :match'
        )->setParameter('match', $id_match)->getResult();

        foreach($user_match as $um){
            
            if($um->getUser() == $user || $um->getUser2() == $user){
                $um->setFinish(1);
                if($win){
                    $um->setScore($scoreWin);
                    $um->setWin(1);
                    $um->getUser()->addPoints(5);
                    $this->notificationMatch($um->getUser(),1);
                    if($um->getMatch()->getType() == "Dobles"){
                        $um->getUser2()->addPoints(5);
                        $this->notificationMatch($um->getUser2(),1);
                    }
                }else{
                    $um->setScore($scoreLoss);
                    $um->setWin(0);
                    $um->getUser()->addPoints(2);
                    $this->notificationMatch($um->getUser(),0);
                    if($um->getMatch()->getType() == "Dobles"){
                        $um->getUser2()->addPoints(2);
                        $this->notificationMatch($um->getUser2(),0);
                    }
                }
            }else{
                $um->setFinish(1);
                $um->setWin(!$win);
                if($win){
                    $um->setScore($scoreLoss);
                    $um->setWin(0);
                    $um->getUser()->addPoints(2);
                    $this->notificationMatch($um->getUser(),0);
                    if($um->getMatch()->getType() == "Dobles"){
                        $um->getUser2()->addPoints(2);
                        $this->notificationMatch($um->getUser2(),0);
                    }
                }else{
                    $um->setScore($scoreWin);
                    $um->setWin(1);
                    $um->getUser()->addPoints(5);
                    $this->notificationMatch($um->getUser(),1);
                    if($um->getMatch()->getType() == "Dobles"){
                        $um->getUser2()->addPoints(5);
                        $this->notificationMatch($um->getUser2(),1);
                    }
                }
            }
            $em->persist($um);
        }

        $match = $em->getRepository('BackendBundle:Match')->findOneById($id_match);

        $match->setStatus(1);

        
        $em->persist($match);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }

    private function notificationMatch($user,$win){

        $winText;
        $pointsText;

        if($win == 1){
            $winText = "ganado";
            $pointsText = 5;
        }else{
            $winText = "perdido";
            $pointsText = 2;
        }

        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$user->getUsername()
        ." ha ".$winText." un partido amistoso (+".$pointsText." puntos de experiencia)");
        $notification->setType("add");
        $notification->setEntity("match");
        $notification->setEnvironment("Frontend");
        $notification->setUser($user);
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();
    }


}