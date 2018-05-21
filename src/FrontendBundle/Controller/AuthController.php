<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;
use BackendBundle\Entity\User;
use BackendBundle\Entity\PlayerUser;
use BackendBundle\Entity\SkillUser;
use BackendBundle\Entity\Notification;

class AuthController extends Controller
{
    public function checkLoginAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $username = $request->get("username");
        $password = $request->get("password");
        
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
  
        $user = $user_manager->loadUserByUsername($username);
        $encoder = $factory->getEncoder($user);
   
        $bool = ($encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) ? "true" : "false";
        $isAdmin=sizeof($user->getRoles());

        $arr = [];
        $arr["status"] = $bool;
        if($isAdmin == 2){
            $arr["isAdmin"] = 1;
        }else{
            $arr["isAdmin"] = 0;
        }
        $arr["id"] = $user->getId();

        return ResponseRest::returnOk($arr);

    }

    public function newUserAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $username = $request->get("username");
        $email = $request->get("email");
        $password = $request->get("password");

        $em = $this->getDoctrine()->getManager();
        $usersRepository = $em->getRepository("BackendBundle:User");

        $userManager = $this->get('fos_user.user_manager');
        $email_exist = $userManager->findUserByEmail($email);
        $username_exist = $userManager->findUserByUsername($username);

        if($email_exist){
            return ResponseRest::returnError("El email ya existe");
        }
        if($username_exist){
            return ResponseRest::returnError("El nombre de usuario ya existe");
        }

        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setEmailCanonical($email);
        $user->setUsernameCanonical($username);
        $user->setLocked(0);
        $user->setEnabled(1);
        $user->setPassword($this->container->get('security.encoder_factory')->getEncoder($user)->encodePassword($password, $user->getSalt()));

        $userPlayer = new PlayerUser();
        $user->setPlayerUser($userPlayer);
        
        $skillUser = new SkillUser();
        $user->setSkillUser($skillUser);

        $em->persist($user);
        $em->persist($userPlayer);
        $em->persist($skillUser);
        $em->flush();
        
        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$user->getUsername()
        ." ha registrado con éxito (id:".$user->getId().")");
        $notification->setType("add");
        $notification->setEntity("user");
        $notification->setEnvironment("Frontend");
        $notification->setUser($user);
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

        $arr = [];
        $arr["id"] = $user->getId();

        return ResponseRest::returnOk($arr);

    }



}