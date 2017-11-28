<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BackendBundle\Entity\Notice;

class NoticeController extends Controller
{
    public function indexAction(){
        
        $con = $this->getDoctrine()->getManager();
        $notice = $con->getRepository('BackendBundle:Notice')->findAll();

        return $this->render('notice/index.html.twig', array('notice' => $notice));

    }

    public function editAction(Request $request, Notice $notice){
        
        $deleteForm = $this->createDeleteForm($notice);

        $editForm = $this->createForm('BackendBundle\Form\NoticeType', $notice);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if(!empty($notice->fileIds)){
                $notice->setImagen($notice->fileIds);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('notice_index', array('id' => $notice->getId()));
        }

        return $this->render('notice/edit.html.twig', array(
            'notice' => $notice,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));

    }

    public function newAction(Request $request)
    {
        $notice = new Notice();
        $form = $this->createForm('BackendBundle\Form\NoticeType', $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!empty($notice->fileIds)){
                $notice->setImgSrc($notice->fileIds);
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

    public function deleteAction(Request $request, Notice $notice)
    {
        $form = $this->createDeleteForm($notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($notice);
            $em->flush();
        }

        return $this->redirectToRoute('notice_index');
    }

    private function createDeleteForm(Notice $notice)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('notice', array('id' => $notice->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}