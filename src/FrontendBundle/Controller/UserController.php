<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;
use BackendBundle\Entity\User;
use BackendBundle\Entity\ClubFavorite;

class UserController extends Controller
{
    public function completeProfileAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id_user = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id_user);

        $firstname = $request->get("firstname");
        $lastname = $request->get("lastname");
        $age = $request->get("age");
        $path = $request->get("path");
        $pathStatus = $request->get("pathstatus");

        $user->getPlayerUser()->setFirstname($firstname);
        $user->getPlayerUser()->setLastname($lastname);
        $user->getPlayerUser()->setAge($age);
        $user->getPlayerUser()->setImgSrc($path);
        $user->setFullPlayer(true);

        $em->persist($user);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }

    public function completeSkillAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id_user = $request->get("id");
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id_user);

        $gameLevel = $request->get("gameLevel");
        $gameStyle = $request->get("gameStyle");
        $typeBackhand = $request->get("typeBackhand");
        $forehand = $request->get("forehand");
        $backhand = $request->get("backhand");
        $volley = $request->get("volley");
        $service = $request->get("service");
        $resistence = $request->get("resistence");

        $user->getSkillUser()->setGameLevel($gameLevel);
        $user->getSkillUser()->setGameStyle($gameStyle);
        $user->getSkillUser()->setTypeBackHand($typeBackhand);
        $user->getSkillUser()->setForehand($forehand);
        $user->getSkillUser()->setBackhand($backhand);
        $user->getSkillUser()->setVolley($volley);
        $user->getSkillUser()->setService($service);
        $user->getSkillUser()->setResistence($resistence);
        $user->setFullGame(true);

        $em->persist($user);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }

    public function getUserAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id = $request->get("id");
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id);

        $club_favorite = $em->getRepository('BackendBundle:ClubFavorite')->findOneByUser($id);

        $friends = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT f FROM BackendBundle:RequestFriend f WHERE (f.user_send = :user OR f.user_receive = :user) AND f.status = 1'
        )->setParameter('user', $id )->getResult();

        $arr = [];
        $arr["id"] = $user->getId();
        $arr["username"] = $user->getUsername();
        $arr["email"] = $user->getEmail();
        $arr["path"] = $user->getPlayerUser()->getImgSrc();
        $arr["firstname"] = $user->getPlayerUser()->getFirstname();
        $arr["lastname"] = $user->getPlayerUser()->getLastname();
        $arr["age"] = $user->getPlayerUser()->getAge();
        $arr["gameStyle"] = $user->getSkillUser()->getGameStyle();
        $arr["gameLevel"] = $user->getSkillUser()->getGameLevel();
        $arr["backhandType"] = $user->getSkillUser()->getTypeBackhand();
        $arr["forehand"] = $user->getSkillUser()->getForehand();
        $arr["backhand"] = $user->getSkillUser()->getBackhand();
        $arr["service"] = $user->getSkillUser()->getService();
        $arr["volley"] = $user->getSkillUser()->getVolley();
        $arr["resistence"] = $user->getSkillUser()->getResistence();
        if($club_favorite != null){
            $arr["googlePlaceId"] = $club_favorite->getGooglePlaceId();
        }
        if($friends != null){
            $arr_aux = [];
            $arr1 = [];
            foreach($friends as $f){
    
                if($f->getUserSend()->getId() == $id){
                    $arr1['id'] = $f->getId();
                    $arr1['id_user'] = $f->getUserReceive()->getId();
                    $arr1['username'] = $f->getUserReceive()->getUsername();
                    $arr1["path"] = $f->getUserReceive()->getPlayerUser()->getImgSrc();
                    $arr1['firstname'] = $f->getUserReceive()->getPlayerUser()->getFirstname();
                    $arr1['lastname'] = $f->getUserReceive()->getPlayerUser()->getLastname();
                    $arr1['gameLevel'] = $f->getUserReceive()->getSkillUser()->getGameLevel();
                    $arr1['gameStyle'] = $f->getUserReceive()->getSkillUser()->getGameStyle();
                }else{
                    $arr1['id'] = $f->getId();
                    $arr1['id_user'] = $f->getUserSend()->getId();
                    $arr1['username'] = $f->getUserSend()->getUsername();
                    $arr1["path"] = $f->getUserSend()->getPlayerUser()->getImgSrc();
                    $arr1['firstname'] = $f->getUserSend()->getPlayerUser()->getFirstname();
                    $arr1['lastname'] = $f->getUserSend()->getPlayerUser()->getLastname();
                    $arr1['gameLevel'] = $f->getUserSend()->getSkillUser()->getGameLevel();
                    $arr1['gameStyle'] = $f->getUserSend()->getSkillUser()->getGameStyle();
                }
                $arr_aux[] = $arr1;
            }
            $arr["friends"] = $arr_aux;
        }
        $requestfriend = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT f FROM BackendBundle:RequestFriend f WHERE (f.user_send = :user OR f.user_receive = :user) AND f.status IS NULL'
        )->setParameter('user', $user->getId())->getResult();
        if($requestfriend != null){
            $arr_aux = [];
            $arr1 = [];
            foreach($requestfriend as $f){
    
                $arr1['id_send'] = $f->getUserSend()->getId();
                $arr1['id_receive'] = $f->getUserReceive()->getId();
                $arr_aux[] = $arr1;
            }
            $arr["requestfriend"] = $arr_aux;
        }
        $requestmatch = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT f FROM BackendBundle:RequestMatch f WHERE (f.user_send = :user OR f.user_receive = :user) AND f.status IS NULL'
        )->setParameter('user', $user->getId())->getResult();
        if($requestmatch != null){
            $arr_aux = [];
            $arr1 = [];
            foreach($requestmatch as $f){
    
                $arr1['id_send'] = $f->getUserSend()->getId();
                $arr1['id_receive'] = $f->getUserReceive()->getId();
                $arr_aux[] = $arr1;
            }
            $arr["requestmatch"] = $arr_aux;
        }

        return ResponseRest::returnOk($arr);
    }

    public function getUserStatusAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id = $request->get("id");
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id);

        $arr = [];
        $arr["fullPlayer"] = $user->getFullPlayer();
        $arr["fullGame"] = $user->getFullGame();
        if($user->getFullPlayer() == 1){
            $arr["path"] = $user->getPlayerUser()->getImgSrc();
        }

        return ResponseRest::returnOk($arr);
    }

    public function getUsersRandomAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $users = $em->createQuery(
        'SELECT u
        FROM BackendBundle:User u
        WHERE u.id != :id AND u.fullGame = 1
        ')->setParameter('id', $id)
        ->getResult();

        $friends = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT f FROM BackendBundle:RequestFriend f 
        WHERE (f.user_send = :id OR f.user_receive = :id)'
        )->setParameter('id', $id)
        ->getResult();

        $arr = [];
        $arr1 = [];

        $usersLimit = 0;

        foreach($users as $u){

            $userNoFriend = 0;

            foreach($friends as $rf){
                if($u->getId() != $rf->getUserReceive()->getId() && $u->getId() != $rf->getUserSend()->getId()){
                    $userNoFriend++;
                }
            }
            
            if(sizeof($friends) == $userNoFriend && $usersLimit < 3){
                $arr1['id'] = $u->getId();
                $arr1['username'] = $u->getUsername();
                $arr1['firstname'] = $u->getPlayerUser()->getFirstname();
                $arr1['lastname'] = $u->getPlayerUser()->getLastname();
                $arr1['gameStyle'] = $u->getSkillUser()->getGameStyle();
                $arr1['gameLevel'] = $u->getSkillUser()->getGameLevel();
                if($u->getFullPlayer() == 1){
                    $arr1["path"] = $u->getPlayerUser()->getImgSrc();
                }
                $arr[] = $arr1;
                $usersLimit++;
            }
            $userNoFriend = 0;
        }

        return ResponseRest::returnOk($arr);
    }

    public function getProfileImageAction($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id);

        $arr = [];
        if($user->getFullPlayer() == 1){
            $arr["path"] = $user->getPlayerUser()->getImgSrc();
        }else{
            $arr["path"] = "default.jpg";
        }

        return ResponseRest::returnOk($arr);
    }


    public function checkIfPlayerUserAction($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id);

        $arr = [];
        if($user->getFullPlayer() == 1){
            $arr["status"] = true;
            $arr["firstname"] = $user->getPlayerUser()->getFirstname();
            $arr["lastname"] = $user->getPlayerUser()->getLastname();
            $arr["age"] = $user->getPlayerUser()->getAge();
            $arr["path"] = $user->getPlayerUser()->getImgSrc();
        }else{
            $arr["status"] = false;
        }

        return ResponseRest::returnOk($arr);
    }



}