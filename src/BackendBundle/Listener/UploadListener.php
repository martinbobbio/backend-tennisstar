<?php

namespace OperaBackBundle\Uploader;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use OperaBackBundle\Entity\Imagen;

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
        $response = $event->getResponse();
        
        return $response;
    }
}