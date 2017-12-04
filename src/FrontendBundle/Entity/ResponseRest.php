<?php

namespace FrontendBundle\Entity;

use Symfony\Component\HttpFoundation\Response;


use Doctrine\ORM\Mapping as ORM;

class ResponseRest
{

    private $data;
    private $status;
    private $error;

    /**
     * Constructor
     */
    public function __construct($status = null, $data = null,  $error = null)
    {   
        $this->status = $status;
        $this->error = $error;
        $this->data = $data;
    }

    public function getResponse(){
        
        return [
            'data' => $this->data,
            'error' => $this->error,
            'status'=> $this->status

        ];

    }

    public function addData($data){
        $this->data[] = $data;
    }

    public function addError($error){
        $this->error[] = $error;
    }

    public function setStatus($status){
        $this->status = $status;
    }


    public function returnOk($data){

        $obj = new ResponseRest();
        $obj->addData($data);
        $obj->setStatus(1);
        
        $response = new Response();
        $response->setContent(json_encode($obj->getResponse()));
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(200);   

        return $response; 
    }

    public function returnError($arr){

        $responseRest = new ResponseRest();
        $responseRest->addError($arr);
        $responseRest->setStatus(0);
        
        $response = new Response();
        $response->setContent(json_encode($responseRest->getResponse()));  
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(200);    
        return $response;    
    }
        
}