<?php
namespace RC\PHPCR\FileAttachInlineBundle\EventListener;


use RC\PHPCR\FileAttachInlineBundle\Event\FileResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use RC\PHPCR\FileAttachInlineBundle\Event\FileErrorEvent;
use RC\PHPCR\FileAttachInlineBundle\Event\FileInfoEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Finder\Finder;



class DownloadSubscriber implements EventSubscriberInterface {


    protected $root_dir;
    protected $web_root;
    protected $container;

    protected $queueRedirects = array();

    CONST TRY_SESSION = 'rc/try/sessions';


    public function __construct($root_dir, $container){
        $this->root_dir = $root_dir;
        $this->web_root = $root_dir.'/../web/';
        $this->container = $container;
    }

    public static function getSubscribedEvents(){

        return array(
            'fai.success.request' => array('postSuccesRequest', 0),
            'fai.failed.validation' => array('postFailedValidation', 0),
            'fai.failed.download'  => array('postFailedDownload', 0),
            'fai.render.inline'  => array('postIgnoreFiles', 0),
        );

    }

    public function postSuccesRequest($args){

    }

    public function postIgnoreFiles(FileResponseEvent $event){
        $event->setResponse(new Response());
    }

    /* TODO Solamente se va realizar un unico redirect, refactorizar queueRedirect */
    public function postFailedValidation(FileErrorEvent $event){

        $files = Finder::create()->in($this->web_root)->name('/'.str_replace(' ', '.', $event->getFilename().'/'))->files();

        if( $files->count() == 1){


            foreach($files as $f){

               $new_file = str_replace($this->web_root, '', $f->getPathname());
               $uri = $this->container->get('router')->generate('_rcfia', array('filename' => $new_file ), true);

               $this->queueRedirects[]= array(
                  'uri' => $uri
               );

            }

        }


    }

    /* TODO controlar mejor la repeticion del evento , una vez máximo */
    public function postFailedDownload(FileResponseEvent $event){

        $redirects = $this->queueRedirects;
        $this->queueRedirects = array();
        $trys = $this->container->get('session')->get(self::TRY_SESSION);
        $trys = (is_array($trys)) ? $trys : array();

        foreach($redirects as $try){

            if( !in_array($try['uri'], $trys ) ){

                $trys[] = $try['uri'];
                $this->container->get('session')->set(self::TRY_SESSION, $trys);
                $event->setResponse(new RedirectResponse($try['uri']));

            }else{
                $this->container->get('session')->remove(self::TRY_SESSION);
            }
        }

    }




}