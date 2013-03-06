<?php

namespace RC\PHPCR\FileAttachInlineBundle\Utils;

use Symfony\Component\HttpFoundation\Response;

class renderInline{
	
    public function download($filepath, $filename ){
    	
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
