<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontendBundle\Entity\ResponseRest;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Notification;
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
        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$user->getUsername()
        ." ha completado su información personal(id:".$user->getId().")");
        $notification->setType("edit");
        $notification->setEntity("playerUser");
        $notification->setEnvironment("Frontend");
        $notification->setUser($user);
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

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
        
        $em->flush();
        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$user->getUsername()
        ." ha completado su información de hablidades(id:".$user->getId().")");
        $notification->setType("edit");
        $notification->setEntity("skillUser");
        $notification->setEnvironment("Frontend");
        $notification->setUser($user);
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

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
        
        $userMatch = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT um FROM BackendBundle:UserMatch um WHERE (um.user = :user or um.user2 = :user) and um.finish = 1'
        )->setParameter('user', $id )->getResult();

        $userTournament = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT ut FROM BackendBundle:UserTournament ut WHERE ut.user = :user'
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
        $arr["level"] = $user->getLevel();
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
        if($userTournament != null){
            $arr1 = [];
            $arr_aux = [];

            $smallSemis = 0;
            $smallFinal = 0;
            $smallChampion = 0;
            $mediumCuartos = 0;
            $mediumSemis = 0;
            $mediumFinal = 0;
            $mediumChampion = 0;
            $bigOctavos = 0;
            $bigCuartos = 0;
            $bigSemis = 0;
            $bigFinal = 0;
            $bigChampion = 0;

            foreach($userTournament as $ut){
                if($ut->getWin() != null){
                    $arr1['id'] = $ut->getTournament()->getId();
                    $arr1['win'] = $ut->getWin();
                    $arr1['title'] = $ut->getTournament()->getTitle();
                    $arr1['instance'] = $ut->getInstance();
                    $arr1['score'] = $ut->getScore()->getScoreString();
                    if($ut->getTournament()->getCount() == 4){
                        if($ut->getInstance() == "Semifinal"){
                            $smallSemis++;
                        }
                        if($ut->getInstance() == "Final"){
                            $smallFinal++;
                            if($ut->getWin() == 1){
                                $smallChampion++;
                            }
                        }
                    }
                    if($ut->getTournament()->getCount() == 8){
                        if($ut->getInstance() == "Cuartos de final"){
                            $mediumCuartos++;
                        }
                        if($ut->getInstance() == "Semifinal"){
                            $mediumSemis++;
                        }
                        if($ut->getInstance() == "Final"){
                            $mediumFinal++;
                            if($ut->getWin() == 1){
                                $mediumChampion++;
                            }
                        }
                    }
                    if($ut->getTournament()->getCount() == 16){
                        if($ut->getInstance() == "Octavos de final"){
                            $bigOctavos++;
                        }
                        if($ut->getInstance() == "Cuartos de final"){
                            $bigCuartos++;
                        }
                        if($ut->getInstance() == "Semifinal"){
                            $bigSemis++;
                        }
                        if($ut->getInstance() == "Final"){
                            $bigFinal++;
                            if($ut->getWin() == 1){
                                $bigChampion++;
                            }
                        }
                    }
                    $arr_aux[] = $arr1;
                }
            }
            $arr["smallSemis"] = $smallSemis;
            $arr["smallFinal"] = $smallFinal;
            $arr["smallChampion"] = $smallChampion;
            $arr["mediumCuartos"] = $mediumCuartos;
            $arr["mediumSemis"] = $mediumSemis;
            $arr["mediumFinal"] = $mediumFinal;
            $arr["mediumChampion"] = $mediumChampion;
            $arr["bigOctavos"] = $bigOctavos;
            $arr["bigCuartos"] = $bigCuartos;
            $arr["bigSemis"] = $bigSemis;
            $arr["bigFinal"] = $bigFinal;
            $arr["bigChampion"] = $bigChampion;
            $arr["tournaments"] = $arr_aux;
        }
        
        if($userMatch != null){
            $arr_aux = [];
            $arr1 = [];
            
            $countSinglesWin = 0;
            $countDoblesWin = 0;
            $countSinglesLoss = 0;
            $countDoblesLoss = 0;
            
            foreach($userMatch as $um){
    
                $arr1['id'] = $um->getId();
                $arr1['win'] = $um->getWin();
                $arr1['matchType'] = $um->getMatch()->getType();
                $arr1['matchTitle'] = $um->getMatch()->getTitle();
                $arr1['score'] = $um->getScore()->getScoreString();
                
                if($um->getWin() == 1){
                    if($um->getMatch()->getType() == "Singles"){
                        $countSinglesWin += 1;
                    }else if ($um->getMatch()->getType() == "Dobles"){
                        $countDoblesWin++;
                    }
                }else{
                    if($um->getMatch()->getType() == "Singles"){
                        $countSinglesLoss++;
                    }else if ($um->getMatch()->getType() == "Dobles"){
                        $countDoblesLoss++;
                    }
                }
                
                $userMatchAux = $this->getDoctrine()->getEntityManager()
                ->createQuery('SELECT um FROM BackendBundle:UserMatch um WHERE um.match = :match'
                 )->setParameter('match', $um->getMatch() )->getResult();
                 
                 if($userMatchAux[0]->getUser() == $user && $userMatchAux[0]->getMatch()->getType() == "Singles" ){
                     $arr1['team1a'] = $userMatchAux[0]->getUser()->getUsername();
                     $arr1['team1aId'] = $userMatchAux[0]->getUser()->getId();
                     $arr1['team2a'] = $userMatchAux[1]->getUser()->getUsername();
                     $arr1['team2aId'] = $userMatchAux[1]->getUser()->getId();
                 }else{
                     $arr1['team2a'] = $userMatchAux[1]->getUser()->getUsername();
                     $arr1['team2a'] = $userMatchAux[0]->getUser()->getUsername();
                     $arr1['team2aId'] = $userMatchAux[1]->getUser()->getId();
                     $arr1['team2aId'] = $userMatchAux[0]->getUser()->getId();
                 }
                 
                 if($userMatchAux[0]->getUser() == $user && $userMatchAux[0]->getMatch()->getType() == "Dobles" ){
                     $arr1['team1a'] = $userMatchAux[0]->getUser()->getUsername();
                     $arr1['team1b'] = $userMatchAux[0]->getUser2()->getUsername();
                     $arr1['team2a'] = $userMatchAux[1]->getUser()->getUsername();
                     $arr1['team2b'] = $userMatchAux[1]->getUser2()->getUsername();
                     $arr1['team1aId'] = $userMatchAux[0]->getUser()->getId();
                     $arr1['team1bId'] = $userMatchAux[0]->getUser2()->getId();
                     $arr1['team2aId'] = $userMatchAux[1]->getUser()->getId();
                     $arr1['team2bId'] = $userMatchAux[1]->getUser2()->getId();
                 }else if($userMatchAux[0]->getUser2() == $user && $userMatchAux[0]->getMatch()->getType() == "Dobles" ){
                     $arr1['team1a'] = $userMatchAux[0]->getUser2()->getUsername();
                     $arr1['team1b'] = $userMatchAux[0]->getUser()->getUsername();
                     $arr1['team2a'] = $userMatchAux[1]->getUser()->getUsername();
                     $arr1['team2b'] = $userMatchAux[1]->getUser2()->getUsername();
                     $arr1['team1aId'] = $userMatchAux[0]->getUser2()->getId();
                     $arr1['team1bId'] = $userMatchAux[0]->getUser()->getId();
                     $arr1['team2aId'] = $userMatchAux[1]->getUser()->getId();
                     $arr1['team2bId'] = $userMatchAux[1]->getUser2()->getId();
                 }else if($userMatchAux[1]->getUser() == $user && $userMatchAux[1]->getMatch()->getType() == "Dobles" ){
                     $arr1['team1a'] = $userMatchAux[1]->getUser()->getUsername();
                     $arr1['team1b'] = $userMatchAux[1]->getUser2()->getUsername();
                     $arr1['team2a'] = $userMatchAux[0]->getUser()->getUsername();
                     $arr1['team2b'] = $userMatchAux[0]->getUser2()->getUsername();
                     $arr1['team1aId'] = $userMatchAux[1]->getUser()->getId();
                     $arr1['team1bId'] = $userMatchAux[1]->getUser2()->getId();
                     $arr1['team2aId'] = $userMatchAux[0]->getUser()->getId();
                     $arr1['team2bId'] = $userMatchAux[0]->getUser2()->getId();
                 }else if($userMatchAux[1]->getUser2() == $user && $userMatchAux[1]->getMatch()->getType() == "Dobles" ){
                     $arr1['team1a'] = $userMatchAux[1]->getUser2()->getUsername();
                     $arr1['team1b'] = $userMatchAux[1]->getUser()->getUsername();
                     $arr1['team2a'] = $userMatchAux[0]->getUser()->getUsername();
                     $arr1['team2b'] = $userMatchAux[0]->getUser2()->getUsername();
                     $arr1['team1aId'] = $userMatchAux[1]->getUser2()->getId();
                     $arr1['team1bId'] = $userMatchAux[1]->getUser()->getId();
                     $arr1['team2aId'] = $userMatchAux[0]->getUser()->getId();
                     $arr1['team2bId'] = $userMatchAux[0]->getUser2()->getId();
                 }
                 
                $arr_aux[] = $arr1;
                
            }
            $arr["countSinglesWin"] = $countSinglesWin;
            $arr["countDoblesWin"] = $countDoblesWin;
            $arr["countSinglesLoss"] = $countSinglesLoss;
            $arr["countDoblesLoss"] = $countDoblesLoss;
            $arr["userMatch"] = $arr_aux;
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

        if($id !== "0"){
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BackendBundle:User')->findOneById($id);

        $arr = [];
        $arr["fullPlayer"] = $user->getFullPlayer();
        $arr["fullGame"] = $user->getFullGame();
        if($user->getFullPlayer() == 1){
            $arr["path"] = $user->getPlayerUser()->getImgSrc();
        }

        return ResponseRest::returnOk($arr);
        
        }else{
            return ResponseRest::returnOk("not found");
        }
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
    
    public function getAllUsersAction(Request $request){

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

        foreach($users as $u){

            $userNoFriend = 0;

            foreach($friends as $rf){
                if($u->getId() != $rf->getUserReceive()->getId() && $u->getId() != $rf->getUserSend()->getId()){
                    $userNoFriend++;
                }
            }
            
            $arr1['id'] = $u->getId();
            $arr1['username'] = $u->getUsername();
            if(sizeof($friends) == $userNoFriend){
                $arr1['isFriend'] = 0;
            }else{
                $arr1['isFriend'] = 1;
            }
            
            $arr1['firstname'] = $u->getPlayerUser()->getFirstname();
            $arr1['lastname'] = $u->getPlayerUser()->getLastname();
            $arr1['gameStyle'] = $u->getSkillUser()->getGameStyle();
            $arr1['gameLevel'] = $u->getSkillUser()->getGameLevel();
            $arr1['level'] = $u->getLevel();
            if($u->getFullPlayer() == 1){
                $arr1["path"] = $u->getPlayerUser()->getImgSrc();
            
            $arr[] = $arr1;
            }
            $userNoFriend = 0;
        }

        return ResponseRest::returnOk($arr);
    }
    
    public function getAllUsersFilterAction(Request $request){

        header("Access-Control-Allow-Origin: *");

        $id = $request->get("id");
        $filter = $request->get("filter");

        $em = $this->getDoctrine()->getManager();
        $users = $em->createQuery(
        'SELECT u
        FROM BackendBundle:User u
        WHERE u.id != :id AND u.fullGame = 1 AND u.username LIKE :filter
        ')->setParameter('id', $id)->setParameter('filter', "%".$filter."%")
        ->getResult();

        $friends = $this->getDoctrine()->getEntityManager()
        ->createQuery('SELECT f FROM BackendBundle:RequestFriend f 
        WHERE (f.user_send = :id OR f.user_receive = :id)'
        )->setParameter('id', $id)
        ->getResult();

        $arr = [];
        $arr1 = [];

        foreach($users as $u){

            $userNoFriend = 0;

            foreach($friends as $rf){
                if($u->getId() != $rf->getUserReceive()->getId() && $u->getId() != $rf->getUserSend()->getId()){
                    $userNoFriend++;
                }
            }
            
            $arr1['id'] = $u->getId();
            $arr1['username'] = $u->getUsername();
            if(sizeof($friends) == $userNoFriend){
                $arr1['isFriend'] = 0;
            }else{
                $arr1['isFriend'] = 1;
            }
            
            $arr1['firstname'] = $u->getPlayerUser()->getFirstname();
            $arr1['lastname'] = $u->getPlayerUser()->getLastname();
            $arr1['gameStyle'] = $u->getSkillUser()->getGameStyle();
            $arr1['gameLevel'] = $u->getSkillUser()->getGameLevel();
            $arr1['level'] = $u->getLevel();
            if($u->getFullPlayer() == 1){
                $arr1["path"] = $u->getPlayerUser()->getImgSrc();
            
            $arr[] = $arr1;
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
    
    public function changePasswordAction(Request $request){

        header("Access-Control-Allow-Origin: *");
        
        $em = $this->getDoctrine()->getManager();

        $id_user = $request->get("id");
        $newPass = $request->get("newPass");
        $pass1 = $request->get("pass1");
        $pass2 = $request->get("pass2");
        
        $user_aux = $em->getRepository('BackendBundle:User')->findOneById($id_user);
        $user_manager = $this->get('fos_user.user_manager');
        $user = $user_manager->loadUserByUsername($user_aux->getUsername());

        $factory = $this->get('security.encoder_factory');
        
        $encoder = $factory->getEncoder($user);
   
        $bool1 = ($encoder->isPasswordValid($user->getPassword(),$pass1,$user->getSalt())) ? 1 : 0;
        $bool2 = ($encoder->isPasswordValid($user->getPassword(),$pass2,$user->getSalt())) ? 1 : 0;
        
        if($bool1 == 1 && $bool2 == 1){
            
            $user->setPassword($this->container->get('security.encoder_factory')->getEncoder($user)
            ->encodePassword($newPass, $user->getSalt()));
            $em->persist($user);
            $em->flush();
            
            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$user->getUsername()
            ." ha cambiado su contraseña(id:".$user->getId().")");
            $notification->setType("edit");
            $notification->setEntity("user");
            $notification->setEnvironment("Frontend");
            $notification->setUser($user);
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();
            
            return ResponseRest::returnOk(1);
        }else{
            return ResponseRest::returnOk(0);
        }
        
    }



}