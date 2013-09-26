<?php

namespace RC\PHPCR\FileAttachInlineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController{
	
	protected $container;
	
	public function __construct($container){
		$this->container = $container;
	}
	
    public function FindAction($filename){
    	
    	$pathinfo = $this->container->get('request')->getPathinfo();
    	$filename = $this->container->getParameter('rcphpcr_file_attach_inline.web_root') . $pathinfo;
    	$validator = $this->container->get('rc.phpcr.file.validator');
    	
    	if($validator->validateFile( array($filename) )){
    		$resolver = $this->container->get('rc.phpcr.resolver.service');
    		$txtname = $resolver->getName($filename);

    		return $this->container->get('rc.phpcr.render.inline')->download($filename, $txtname );
    	}
    }
}
