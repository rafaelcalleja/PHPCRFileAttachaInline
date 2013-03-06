<?php 
namespace RC\PHPCR\FileAttachInlineBundle\Resolver;

use RC\PHPCR\FileAttachInlineBundle\Resolver\Providers;

class Preferencer{
	
	protected $resolvers;
	
	public function __construct($resolvers, $container){
		
		if(is_array($resolvers) && count($resolvers) > 0){
			foreach($resolvers as $resolver){
				$classname = 'RC\\PHPCR\\FileAttachInlineBundle\\Resolver\\Providers\\'.ucfirst($resolver).'Provider';
				
				if(class_exists($classname)){
					$this->resolvers[$resolver] = $container->get("rc.phpcr.$resolver.provider"); 
				}
			}
		}
		
	}
	
	public function getName($filename){
		if(is_array($this->resolvers) && count($this->resolvers) > 0){
			
			foreach($this->resolvers as $r){
				$name = $r->getName($filename);
				if($name !== FALSE) return $name;
			}
			
		}
		
		return false;
	}
	
}