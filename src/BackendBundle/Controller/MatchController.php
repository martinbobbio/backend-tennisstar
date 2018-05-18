<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Match;
use BackendBundle\Entity\UserMatch;
use BackendBundle\Entity\Score;
use BackendBundle\Entity\Notification;

class MatchController extends Controller
{

    //---------------------INDEX------------------------------

    public function indexAction(){
        
        $con = $this->getDoctrine()->getManager();
        $match = $con->getRepository('BackendBundle:Match')->findAll();

        foreach($match as $m){
            $players = $con->getRepository('BackendBundle:UserMatch')->findByMatch($m);
            $m->setPlayers($players);
        }

        $delete_form = $this->createCustomForm(':ID','DELETE','match_delete');

        return $this->render('match/index.html.twig', array('match' => $match,'delete_form' => $delete_form->createView() ));

    }

    //---------------------EDIT------------------------------

    public function editAction(Request $request, Match $match){
        
        $editForm = $this->createForm('BackendBundle\Form\MatchType', $match);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $match->setDateMatch($match->getDateMatch()->setTime(
                $match->getDateHour()->format('H'),
                $match->getDateHour()->format('i'))
            );
            $this->getDoctrine()->getManager()->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha editado el partido (id:".$match->getId().")");
            $notification->setType("edit");
            $notification->setEntity("match");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('match_index', array('id' => $match->getId()));
        }

        return $this->render('match/edit.html.twig', array(
            'match' => $match,
            'edit_form' => $editForm->createView(),
        ));

    }

    //---------------------NEW------------------------------

    public function newAction(Request $request)
    {
        $match = new Match();
        $form = $this->createForm('BackendBundle\Form\MatchType', $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $match->setStatus(0);
            $match->setDateMatch($match->getDateMatch()->setTime(
                $match->getDateHour()->format('H'),
                $match->getDateHour()->format('i'))
            );
            $score = new Score();
            $score->setStatus(0);
            $match->setScore($score);

            $em = $this->getDoctrine()->getManager();
            $em->persist($match);
            $em->persist($score);
            $em->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha agregado el partido (id:".$match->getId().")");
            $notification->setType("add");
            $notification->setEntity("match");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('match_index');
        }

        return $this->render('match/new.html.twig', array(
            'match' => $match,
            'form' => $form->createView(),
        ));
    }

    //---------------------DELETE------------------------------

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $match = $em->getRepository('BackendBundle:Match')->find($id);

        $form = $this->createCustomForm($match->getId(),'DELETE', 'match_delete');
        $form->handleRequest($request);

        $em->remove($match->getScore());
        $em->remove($match);
        $em->flush();
        
        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
        ." ha borrado el partido (id:".$id.")");
        $notification->setType("delete");
        $notification->setEntity("match");
        $notification->setEnvironment("Backend");
        $notification->setUser($this->container->get('security.context')->getToken()->getUser());
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('match_index');

    }

    //----------------------PLAYERS----------------------------------
    public function editPlayersAction(Request $request, Match $match){

        $userMatch = new UserMatch();
        $messageError;

        if($match->getType() == "Singles"){
            $messageError = "El maximo de jugadores es 2";
            $editForm = $this->createForm('BackendBundle\Form\UserMatchType', $userMatch);
        }else if($match->getType() == "Dobles"){
            $messageError = "El maximo de jugadores por equipo es 2";
            $editForm = $this->createForm('BackendBundle\Form\UserMatch2Type', $userMatch);
        }

        
        $editForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $maxPlayers;

            if(($match->getType() == "Singles" && sizeof($userMatch->getUser()->getValues()) <= 2)){

                $userMatchRemove = $em->getRepository('BackendBundle:UserMatch')->findByMatch($match);
                foreach($userMatchRemove as $u){
                    $em->remove($u);
                    $em->flush();
                }
                

                foreach($userMatch->getUser()->getValues() as $u){

                $userMatch = new UserMatch();
                $userMatch->setUser($u);
                $userMatch->setUser2(null);
                $userMatch->setMatch($match);

                $em->persist($userMatch);
                $em->flush();

                $notification = new Notification();
                $notification->setTitle(
                "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
                ." ha editado los jugadores del partido de singles");
                $notification->setType("edit");
                $notification->setEntity("match");
                $notification->setEnvironment("Backend");
                $notification->setUser($this->container->get('security.context')->getToken()->getUser());
                $this->getDoctrine()->getManager()->persist($notification);
                $this->getDoctrine()->getManager()->flush();
                }

            }else if(($match->getType() == "Dobles" && sizeof($userMatch->getUser()->getValues()) <= 2 && sizeof($userMatch->getUser2()->getValues()) <= 2)){

                $userMatchRemove = $em->getRepository('BackendBundle:UserMatch')->findByMatch($match);
                foreach($userMatchRemove as $u){
                    $em->remove($u);
                    $em->flush();
                }
                    
                    if(sizeof($userMatch->getUser()->getValues()) == 1){
                        $userMatch_aux = new UserMatch();
                        $userMatch_aux->setUser($userMatch->getUser()->getValues()[0]);
                        $userMatch_aux->setUser2(null);
                        $userMatch_aux->setMatch($match);
                        $em->persist($userMatch_aux);
                    }
                    if(sizeof($userMatch->getUser()->getValues()) == 2){
                        $userMatch_aux = new UserMatch();
                        $userMatch_aux->setUser($userMatch->getUser()->getValues()[0]);
                        $userMatch_aux->setUser2($userMatch->getUser()->getValues()[1]);
                        $userMatch_aux->setMatch($match);
                        $em->persist($userMatch_aux);
                    }

                    if(sizeof($userMatch->getUser2()->getValues()) == 1){
                        $userMatch_aux = new UserMatch();
                        $userMatch_aux->setUser($userMatch->getUser2()->getValues()[0]);
                        $userMatch_aux->setUser2(null);
                        $userMatch_aux->setMatch($match);
                        $em->persist($userMatch_aux);
                    }
                    if(sizeof($userMatch->getUser2()->getValues()) == 2){
                        $userMatch_aux = new UserMatch();
                        $userMatch_aux->setUser($userMatch->getUser2()->getValues()[0]);
                        $userMatch_aux->setUser2($userMatch->getUser2()->getValues()[1]);
                        $userMatch_aux->setMatch($match);
                        $em->persist($userMatch_aux);
                    }

                    $em->flush();

                    $notification = new Notification();
                    $notification->setTitle(
                    "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
                    ." ha editado los jugadores del partido de dobles");
                    $notification->setType("edit");
                    $notification->setEntity("match");
                    $notification->setEnvironment("Backend");
                    $notification->setUser($this->container->get('security.context')->getToken()->getUser());
                    $this->getDoctrine()->getManager()->persist($notification);
                    $this->getDoctrine()->getManager()->flush();
                    
                }else{
                return $this->render('match/player.html.twig', array(
                    'match' => $match,
                    'edit_form' => $editForm->createView(),
                    'message' => $messageError
                ));
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('match_index', array('id' => $match->getId()));
            }

        $usersMatch = $em->getRepository('BackendBundle:UserMatch')->findByMatch($match);
        return $this->render('match/player.html.twig', array(
            'match' => $match,
            'edit_form' => $editForm->createView(),
            'players' => $usersMatch
        ));

    }

    //---------------------FORMS------------------------------

    private function createCustomForm($id,$method,$route){
        return $this->createFormBuilder()->setAction($this->generateUrl($route, array ('id' => $id)))->setMethod($method)->getForm();
    }
}