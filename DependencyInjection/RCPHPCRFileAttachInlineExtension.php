<?php

namespace RC\PHPCR\FileAttachInlineBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Alias;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RCPHPCRFileAttachInlineExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
       // $container->setAlias('rcphpcr_file_attach_inline', new Alias('rc_phpcr_fia'));
        $container->setParameter('rcphpcr_file_attach_inline.web_root', $config['web_root']);
        $container->setParameter('rcphpcr_file_attach_inline.controller_action', $config['controller_action']);
        $container->setParameter('rcphpcr_file_attach_inline.mimetypes', $config['mimetypes']);
        $container->setParameter('rcphpcr_file_attach_inline.extensions', $config['extensions']);
        $container->setParameter('rcphpcr_file_attach_inline.max_filesize', $config['max_filesize']);
        $container->setParameter('rcphpcr_file_attach_inline.preference', $config['preference']);
        foreach($config['providers'] as $key => $provider){
        	$container->setParameter("rcphpcr_file_attach_inline.providers.$key", $provider);
        }
        
        
        

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
