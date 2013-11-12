<?php

namespace RC\PHPCR\FileAttachInlineBundle\Controller;

use RC\PHPCR\FileAttachInlineBundle\Event\FAIEvent;
use RC\PHPCR\FileAttachInlineBundle\Event\FileInfoEvent;
use RC\PHPCR\FileAttachInlineBundle\Event\FileResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController{
	
	protected $container;
	
	public function __construct($container){
		$this->container = $container;
	}
	
    public function FindAction($filename){

    	$pathinfo = $this->container->get('request')->getPathinfo();
    	$filename = urldecode($this->container->getParameter('rcphpcr_file_attach_inline.web_root') . $pathinfo);
    	$validator = $this->container->get('rc.phpcr.file.validator');

    	if($validator->validateFile( array($filename) )){
    		$resolver = $this->container->get('rc.phpcr.resolver.service');
    		$txtname = $resolver->getName($filename);

    		return $this->container->get('rc.phpcr.render.inline')->download($filename, $txtname );
    	}

        $event = new FileResponseEvent($filename, $pathinfo, 0, false );
        $response = $this->container->get('event_dispatcher')->dispatch(FAIEvent::FAI_FAILED_DOWNLOAD, $event )->getResponse();

        if($response instanceof RedirectResponse){

            return $response;
        }

        throw new NotFoundHttpException('Page not found');


    }
}
