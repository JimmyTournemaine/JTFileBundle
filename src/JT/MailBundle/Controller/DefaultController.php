<?php

namespace JT\MailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JTMailBundle:Default:index.html.twig');
    }
}
