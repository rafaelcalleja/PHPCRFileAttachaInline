<?php
namespace RC\PHPCR\FileAttachInlineBundle\Event;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

class FileInfoEvent extends SymfonyEvent{

    protected $filename;
    protected $filepath;
    protected $size;
    protected $mimetype;

    public function __construct($filename, $filepath, $size, $mimetype){
        $this->filename = rawurldecode($filename);
        $this->filepath = rawurldecode($filepath);
        $this->size = $size;
        $this->mimetype = $mimetype;
    }

    public function getFilename(){
        return $this->filename;
    }

    public function getPath(){
        return $this->filepath;
    }

    public function getSize(){
        return $this->size;
    }

    public function getMimeType(){
        return $this->mimetype;
    }


}