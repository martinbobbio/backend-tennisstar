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
   
        $bool = ($encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) ? true : false;
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
        
        $message = new \Swift_Message();
        $swift_Image = new \Swift_Image();
        $vista = $this->render('Emails/newuser.html.twig');
        
        $name = $user->getUsername();
        $title = "¡Gracias por registrarte en tennisstar!";
        $messageText = "Lorem";
        
        $logo = '<img src="' . $message->embed($swift_Image->fromPath('images/logo.png')) .'" alt="tennisstar" style="width:100px;"/>';
        $facebook1 = '<img src="' . $message->embed($swift_Image->fromPath('images/facebook.png')) .'" alt="facebook" height="25" width="25"/>';
        $twitter1 = '<img src="' . $message->embed($swift_Image->fromPath('images/twitter.png')) .'" alt="twitter" height="28" width="28"/>';
        $instagram1 = '<img src="' . $message->embed($swift_Image->fromPath('images/instagram.png')) .'" alt="instagram" height="24" width="24"/>';
        
        $body = str_replace(
            array ("*logo","*name", "*facebook1","*twitter1","*instagram1", "*title","*message"), 
            array ($logo, $name, $facebook1, $twitter1, $instagram1,$title,$messageText), 
            $vista);
        
        $message->setSubject('Bienvenido a tennisstar')->setFrom(['no-reply@tennisstar.com' => 'Tennisstar'])->setTo($user->getEmail())->setBody($body,'text/html');
        $this->get('mailer')->send($message);

        return ResponseRest::returnOk($arr);

    }
    
    public function newPasswordAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $email = $request->get("email");
        $em = $this->getDoctrine()->getManager();
        
        $user_manager = $this->get('fos_user.user_manager');
        $user = $user_manager->findUserByEmail($email);

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);
        $result = "";
        for ($i = 0, $result = ''; $i < 8; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        
        if($user == null){
            return ResponseRest::returnError("El email no existe");
        }
        if($user->getPasswordreset() == 3){
            return ResponseRest::returnError("Has excedido el máximo de reseteos de contraseñas");
        }
        
        $user->setPassword($this->container->get('security.encoder_factory')->getEncoder($user)
        ->encodePassword($result, $user->getSalt()));
        $user->setPasswordreset($user->getPasswordreset()+1);
        $em->persist($user);
        $em->flush();
        
        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$user->getUsername()
        ." ha solicitado el cambio de contraseña(id:".$user->getId().")");
        $notification->setType("edit");
        $notification->setEntity("user");
        $notification->setEnvironment("Frontend");
        $notification->setUser($user);
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();
        
        $message = new \Swift_Message();
        $swift_Image = new \Swift_Image();
        $vista = $this->render('Emails/newpassword.html.twig');
        
        $name = $user->getUsername();
        $title = "Has solicitado una nueva contraseña";
        $messageText = "Te enviamos tu nueva contraseña.";
        
        $logo = '<img src="' . $message->embed($swift_Image->fromPath('images/logo.png')) .'" alt="tennisstar" style="width:100px;"/>';
        $facebook1 = '<img src="' . $message->embed($swift_Image->fromPath('images/facebook.png')) .'" alt="facebook" height="25" width="25"/>';
        $twitter1 = '<img src="' . $message->embed($swift_Image->fromPath('images/twitter.png')) .'" alt="twitter" height="28" width="28"/>';
        $instagram1 = '<img src="' . $message->embed($swift_Image->fromPath('images/instagram.png')) .'" alt="instagram" height="24" width="24"/>';
        
        $body = str_replace(
            array ("*logo","*name", "*facebook1","*twitter1","*instagram1", "*title","*message","*passwordText"), 
            array ($logo, $name, $facebook1, $twitter1, $instagram1,$title,$messageText,$result), 
            $vista);
        
        $message->setSubject('Cambio de contraseña')->setFrom(['no-reply@tennisstar.com' => 'Tennisstar'])->setTo($user->getEmail())->setBody($body,'text/html');
        $this->get('mailer')->send($message);
        
        return ResponseRest::returnOk("Se ha enviado la contraseña a tu email");
        
    }



}