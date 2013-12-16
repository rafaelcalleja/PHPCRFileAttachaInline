<?php

namespace RC\PHPCR\FileAttachInlineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rcphpcr_file_attach_inline', 'array');
        $rootNode
	        ->children()
	        	->scalarNode('web_root')->defaultValue('%kernel.root_dir%/../web')->end()
	        	->scalarNode('controller_action')->defaultValue('rc_phpcr.controller:FindAction')->end()
	        	->scalarNode('max_filesize')->defaultValue('3072k')->end()
	        	->arrayNode('extensions')->defaultValue(array('pdf'))->prototype('scalar')->end()->end()
                ->arrayNode('ignore_files')
                    ->defaultValue(array())
                    ->prototype('scalar')->end()
                ->end()
	        	->arrayNode('mimetypes')
	        		->defaultValue(array())
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('preference')
                    ->defaultValue(array())
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('providers')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    	->children()
                    		->arrayNode('locales')->prototype('scalar')->defaultValue(array('es', 'en'))->end()->end()
                    		->scalarNode('field')->defaultValue('filePath')->end()
                    		->scalarNode('multilang')->defaultValue(true)->end()
                    		->scalarNode('field_title')->defaultValue('phpcr_locale:{_locale}-title')->end()
                    	->end()
                ->end()
	        ->end()
	    ->end();

        return $treeBuilder;
    }
}
