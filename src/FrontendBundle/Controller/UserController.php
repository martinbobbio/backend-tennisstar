<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;
use BackendBundle\Entity\User;

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

        $user->getPlayerUser()->setFirstname($firstname);
        $user->getPlayerUser()->setLastname($lastname);
        $user->getPlayerUser()->setAge($age);

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

        $em->persist($user);
        $em->flush();

        return ResponseRest::returnOk("ok");

    }



}