<?php
namespace RC\PHPCR\FileAttachInlineBundle\Resolver\Providers;

use RC\PHPCR\FileAttachInlineBundle\Resolver\FileNameInterface; 
  
class FilesystemProvider implements FileNameInterface{
	
	
	public function getName($filepath){
		
		$file = realpath($filepath);
		
		if(file_exists($file)){
			$info = pathinfo($file);
			return $info['basename'];
		}
		
		return false;
	}
	
	
}