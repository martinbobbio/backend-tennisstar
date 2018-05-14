<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Tournament;
use BackendBundle\Entity\UserTournament;

class TournamentController extends Controller
{

    //---------------------INDEX------------------------------

    public function indexAction(){
        
        $con = $this->getDoctrine()->getManager();
        $tournament = $con->getRepository('BackendBundle:Tournament')->findAll();

        $delete_form = $this->createCustomForm(':ID','DELETE','tournament_delete');

        return $this->render('tournament/index.html.twig', array('tournament' => $tournament,'delete_form' => $delete_form->createView() ));

    }

    //---------------------EDIT------------------------------

    public function editAction(Request $request, Tournament $tournament){
        
        $editForm = $this->createForm('BackendBundle\Form\TournamentType', $tournament);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $tournament->setDateTournament($tournament->getDateTournament()->setTime(
                $tournament->getDateHour()->format('H'),
                $tournament->getDateHour()->format('i'))
            );
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tournament_index', array('id' => $tournament->getId()));
        }

        return $this->render('tournament/edit.html.twig', array(
            'tournament' => $tournament,
            'edit_form' => $editForm->createView(),
        ));

    }

    //---------------------NEW------------------------------

    public function newAction(Request $request)
    {
        $tournament = new Tournament();
        $form = $this->createForm('BackendBundle\Form\TournamentType', $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $tournament->setStatus(0);
            $tournament->setDateTournament($tournament->getDateTournament()->setTime(
                $tournament->getDateHour()->format('H'),
                $tournament->getDateHour()->format('i'))
            );

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

            return $this->redirectToRoute('tournament_index');
        }

        return $this->render('tournament/new.html.twig', array(
            'tournament' => $tournament,
            'form' => $form->createView(),
        ));
    }

    public function indexPlayersAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $userTournament = $em->getRepository('BackendBundle:UserTournament')->findByTournament($id);

        return $this->render('usertournament/index.html.twig', array('userTournament' => $userTournament));

    }

    //---------------------DELETE------------------------------

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $tournament = $em->getRepository('BackendBundle:Tournament')->find($id);
        $userTournament = $em->getRepository('BackendBundle:UserTournament')->findByTournament($id);

        $form = $this->createCustomForm($tournament->getId(),'DELETE', 'tournament_delete');
        $form->handleRequest($request);

        foreach($userTournament as $ut){
            $em->remove($ut);
            $em->flush();
        }
        $em->remove($tournament);
        $em->flush();

        return $this->redirectToRoute('tournament_index');

    }

    //---------------------FORMS------------------------------

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

    private function createCustomForm($id,$method,$route){
        return $this->createFormBuilder()->setAction($this->generateUrl($route, array ('id' => $id)))->setMethod($method)->getForm();
    }
}