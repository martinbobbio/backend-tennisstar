<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Notice;

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

            if(!empty($notice->fileIds)){
                $notice->setImgSrc("uploads/notice/".$notice->fileIds);
            }

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
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($notice);
            $em->flush();

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

        if($form->isSubmitted() && $form->isValid()){
            
            if($request->isXMLHttpRequest()){
                
                $em->remove($notice) ;
                $em->flush();   
                return new Response(json_encode(array('removed' => 1,'message' => 'Noticia borrado')),200, array('Content-Type' => 'application/json'));
            }

            $em->remove($notice) ;
            $em->flush();

            return $this->redirectToRoute('notice_index');
        }
    }

    //---------------------FORMS------------------------------

    private function createCustomForm($id,$method,$route){
        return $this->createFormBuilder()->setAction($this->generateUrl($route, array ('id' => $id)))->setMethod($method)->getForm();
    }
}