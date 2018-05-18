<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Notice;
use BackendBundle\Entity\Notification;

class NoticeController extends Controller
{

    //---------------------INDEX------------------------------

    public function indexAction(){
        
        $con = $this->getDoctrine()->getManager();
        $notice = $con->getRepository('BackendBundle:Notice')->findAll();

        $delete_form = $this->createCustomForm(':ID','DELETE','notice_delete');

        return $this->render('notice/index.html.twig', array('notice' => $notice,'delete_form' => $delete_form->createView() ));

    }

    //---------------------EDIT------------------------------

    public function editAction(Request $request, Notice $notice){
        
        $editForm = $this->createForm('BackendBundle\Form\NoticeType', $notice);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $notice->setUser($this->container->get('security.context')->getToken()->getUser());
            $urlYoutube = $notice->getVideoLink();
            $urlYoutubeStatus = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $urlYoutube, $match);
            if($urlYoutubeStatus == 1){
                $notice->setVideoLink($match[1]);
            }else{
                $notice->setVideoLink(null);
            }

            if(!empty($notice->fileIds)){
                $notice->setImgSrc("uploads/notice/".$notice->fileIds);
            }

            $this->getDoctrine()->getManager()->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha editado la noticia (id:".$notice->getId().")");
            $notification->setType("edit");
            $notification->setEntity("notice");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('notice_index', array('id' => $notice->getId()));
        }

        return $this->render('notice/edit.html.twig', array(
            'notice' => $notice,
            'edit_form' => $editForm->createView(),
        ));

    }

    //---------------------NEW------------------------------

    public function newAction(Request $request)
    {
        $notice = new Notice();
        $form = $this->createForm('BackendBundle\Form\NoticeType', $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!empty($notice->fileIds)){
                $notice->setImgSrc("uploads/notice/".$notice->fileIds);
            }

            $notice->setUser($this->container->get('security.context')->getToken()->getUser());
            $urlYoutube = $notice->getVideoLink();
            $urlYoutubeStatus = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $urlYoutube, $match);
            if($urlYoutubeStatus == 1){
                $notice->setVideoLink($match[1]);
            }else{
                $notice->setVideoLink(null);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($notice);
            $em->flush();

            $notification = new Notification();
            $notification->setTitle(
            "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
            ." ha agregado la noticia (id:".$notice->getId().")");
            $notification->setType("add");
            $notification->setEntity("notice");
            $notification->setEnvironment("Backend");
            $notification->setUser($this->container->get('security.context')->getToken()->getUser());
            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('notice_index');
        }

        return $this->render('notice/new.html.twig', array(
            'notice' => $notice,
            'form' => $form->createView(),
        ));
    }

    //---------------------DELETE------------------------------

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $notice = $em->getRepository('BackendBundle:Notice')->find($id);

        $form = $this->createCustomForm($notice->getId(),'DELETE', 'notice_delete');
        $form->handleRequest($request);

        $em->remove($notice);
        $em->flush();

        $notification = new Notification();
        $notification->setTitle(
        "El usuario ".$this->container->get('security.context')->getToken()->getUser()->getUsername()
        ." ha borrado la noticia (id:".$id.")");
        $notification->setType("delete");
        $notification->setEntity("notice");
        $notification->setEnvironment("Backend");
        $notification->setUser($this->container->get('security.context')->getToken()->getUser());
        $this->getDoctrine()->getManager()->persist($notification);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('notice_index');
    }

    //---------------------FORMS------------------------------

    private function createCustomForm($id,$method,$route){
        return $this->createFormBuilder()->setAction($this->generateUrl($route, array ('id' => $id)))->setMethod($method)->getForm();
    }
}