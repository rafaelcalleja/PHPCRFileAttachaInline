<?php

namespace RC\PHPCR\FileAttachInlineBundle\Utils;


use RC\PHPCR\FileAttachInlineBundle\Event\FAIEvent;
use RC\PHPCR\FileAttachInlineBundle\Event\FileInfoEvent;
use Symfony\Component\HttpFoundation\Response;

class renderInline{

    protected $dispatcher;

    public function __construct($dispatcher){
        $this->dispatcher = $dispatcher;
    }

    public function download($filepath, $filename ){

        $event = new FileInfoEvent($filename, $filepath, filesize($filepath), mime_content_type($filepath));
        $this->dispatcher->dispatch(FAIEvent::FAI_SUCCESS_REQUEST, $event );


    	$response = new Response();
    		
    	$response->headers->set('Cache-Control', 'private');
    	$response->headers->set('Content-type', mime_content_type($filepath) );
    	$response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
    	$response->headers->set('Content-length', filesize($filepath));
    	$response->sendHeaders();
    		
    	$response->setContent(readfile($filepath));
    		
    	return $response;
    }


}
