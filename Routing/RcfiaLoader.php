<?php

namespace RC\PHPCR\FileAttachInlineBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RcfiaLoader extends Loader
{
    private $controllerAction, $extensions;

    public function __construct($controller, $action, $extensions){
    	//$action = strstr($action, ':');
    	//$controller = str_replace("\\", '.', $controller );
        //$this->controllerAction = $controller.$action;
        //$this->controllerAction = "RC\PHPCR\FileAttachInlineBundle\Controller\DefaultController:findAction";
        $this->controllerAction = $action;
        
        $this->extensions = $extensions;
    }
    

    public function supports($resource, $type = null)
    {
        return $type === 'rcfia';
    }

    public function load($resource, $type = null)
    {
    	$this->extensions = array_merge($this->extensions, array_map('strtoupper', $this->extensions));
        $requirements = array('_method' => 'GET', 'filename' => '.*\.('.implode('|', $this->extensions).')$');
        $routes       = new RouteCollection();

        
        		
                $defaults = array(
                    '_controller' => empty($config['controller_action']) ? $this->controllerAction : $config['controller_action'],
                );
                

                $routeRequirements = $requirements;
                $routeDefaults = $defaults;
                $routeOptions = array();

                if (isset($config['route']['requirements'])) {
                    $routeRequirements = array_merge($routeRequirements, $config['route']['requirements']);
                }
                if (isset($config['route']['defaults'])) {
                    $routeDefaults = array_merge($routeDefaults, $config['route']['defaults']);
                }
                if (isset($config['route']['options'])) {
                    $routeOptions = array_merge($routeOptions, $config['route']['options']);
                }
                
                

                $routes->add('_rcfia', new Route(
                    '/{filename}',
                    $routeDefaults,
                    $routeRequirements,
                    $routeOptions
                ));


        return $routes;
    }
}
