<?php
namespace RC\PHPCR\FileAttachInlineBundle\Event;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

class FileResponseEvent extends FileInfoEvent {

    protected $response;

    public function __construct($filename, $filepath, $size, $mimetype){
        parent::__construct($filename, $filepath, $size, $mimetype);
    }

    public function setResponse($response){
        $this->response = $response;
    }

    public function getResponse(){
        return $this->response;
    }



}