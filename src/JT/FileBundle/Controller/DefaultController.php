<?php

namespace JT\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JTFileBundle:Default:index.html.twig');
    }
}
