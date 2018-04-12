<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;
use BackendBundle\Entity\RequestFriend;

class RequestFriendController extends Controller
{

    public function getFriendsAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id_user = $request->get("id");
        
        $query = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT f FROM BackendBundle:RequestFriend f WHERE (f.user_send = :user OR f.user_receive = :user) AND f.status = 1'
        )->setParameter('user', $id_user );;

        $friends = $query->getResult();

        $arr = [];
        $arr1 = [];

        foreach($friends as $f){

            if($f->getUserSend()->getId() == $id_user){
                $arr1['id'] = $f->getId();
                $arr1['id_user'] = $f->getUserReceive()->getId();
                $arr1['username'] = $f->getUserReceive()->getUsername();
                $arr1['firstname'] = $f->getUserReceive()->getPlayerUser()->getFirstname();
                $arr1['lastname'] = $f->getUserReceive()->getPlayerUser()->getLastname();
                $arr1['gameLevel'] = $f->getUserReceive()->getSkillUser()->getGameLevel();
                $arr1['gameStyle'] = $f->getUserReceive()->getSkillUser()->getGameLevel();
            }else{
                $arr1['id'] = $f->getId();
                $arr1['id_user'] = $f->getUserSend()->getId();
                $arr1['username'] = $f->getUserSend()->getUsername();
                $arr1['firstname'] = $f->getUserSend()->getPlayerUser()->getFirstname();
                $arr1['lastname'] = $f->getUserSend()->getPlayerUser()->getLastname();
                $arr1['gameLevel'] = $f->getUserSend()->getSkillUser()->getGameLevel();
                $arr1['gameStyle'] = $f->getUserSend()->getSkillUser()->getGameLevel();
            }
            $arr[] = $arr1;
        }

        return ResponseRest::returnOk($arr);
    }

    public function getRequestsAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id_user = $request->get("id");
        
        $query = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT f FROM BackendBundle:RequestFriend f WHERE (f.user_send = :user OR f.user_receive = :user) AND f.status IS null'
        )->setParameter('user', $id_user );;

        $friends = $query->getResult();

        $arr = [];
        $arr1 = [];

        foreach($friends as $f){

            if($f->getUserSend()->getId() == $id_user){
                $arr1['id'] = $f->getId();
                $arr1['id_user'] = $f->getUserReceive()->getId();
                $arr1['username'] = $f->getUserReceive()->getUsername();
                $arr1['firstname'] = $f->getUserReceive()->getPlayerUser()->getFirstname();
                $arr1['lastname'] = $f->getUserReceive()->getPlayerUser()->getLastname();
                $arr1['gameLevel'] = $f->getUserReceive()->getSkillUser()->getGameLevel();
                $arr1['gameStyle'] = $f->getUserReceive()->getSkillUser()->getGameLevel();
            }else{
                $arr1['id'] = $f->getId();
                $arr1['id_user'] = $f->getUserSend()->getId();
                $arr1['username'] = $f->getUserSend()->getUsername();
                $arr1['firstname'] = $f->getUserSend()->getPlayerUser()->getFirstname();
                $arr1['lastname'] = $f->getUserSend()->getPlayerUser()->getLastname();
                $arr1['gameLevel'] = $f->getUserSend()->getSkillUser()->getGameLevel();
                $arr1['gameStyle'] = $f->getUserSend()->getSkillUser()->getGameLevel();
            }
            $arr[] = $arr1;
        }

        return ResponseRest::returnOk($arr);
    }

    public function sendRequestFriendAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_send = $request->get("id_send");
        $user_send = $em->getRepository('BackendBundle:User')->findOneById($id_send);
        $id_receive = $request->get("id_receive");
        $user_receive = $em->getRepository('BackendBundle:User')->findOneById($id_receive);
        
        $request_friend = new RequestFriend();
        $request_friend->setUserSend($user_send);
        $request_friend->setUserReceive($user_receive);

        $em->persist($request_friend);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }

    public function sendResponseFriendAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();

        $id_request_friend = $request->get("id");
        $status_request_friend = $request->get("status");
        $request_friend = $em->getRepository('BackendBundle:RequestFriend')->findOneById($id_request_friend);
        
        $request_friend->setStatus($status_request_friend);

        $em->persist($request_friend);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }
    

}