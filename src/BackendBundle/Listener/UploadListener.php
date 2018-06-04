<?php

namespace BackendBundle\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    
    public function onUpload(PostPersistEvent $event)
    {
        $request_file = $event->getFile();
        $request = $event->getRequest();
        $extension = strtolower($request_file->getExtension());

        if($extension == "jpg" || $extension == "png" || $extension == "jpeg"){

        $response = $event->getResponse();
        $response['success'] = true;
        $response["files"] = array(
        array(
            "type" => $request_file->getMimeType(),
            "path" => $request_file->getFileName()
        ));
        return "ok";

        }else{
            return "error";
        }
    }


    
}