<?php

namespace RC\PHPCR\FileAttachInlineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FileAttachInlineBundle:Default:index.html.twig', array('name' => $name));
    }
}
