<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Notice;
use FrontendBundle\Entity\ResponseRest;

class NoticeController extends Controller
{
    public function getNoticeAction(){
        
        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();
        $notice = $em->getRepository('BackendBundle:Notice')->findAll();

        $arr = [];
        $arr1 = [];

        foreach($notice as $n){

            $arr1['title'] = $n->getTitle();
            $arr1['description'] = $n->getDescription();
            $arr1['imgsrc'] = $n->getImgSrc();
            $arr1['videoLink'] = $n->getVideoLink();
            $arr1['created'] = $n->getCreateAt();
            $arr1['updated'] = $n->getUpdateAt();
            $arr[] = $arr1;
        }

        return ResponseRest::returnOk($arr);

    }


}