<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if(!$this->isGranted('IS_AUTHENTICATED_REMEMBERED', $this->getUser()))
            return $this->redirectToRoute('welcome');

        return $this->render('default/homepage.html.twig');
    }

    /**
     * @Route("/welcome", name="welcome")
     */
    public function landingAction(Request $request)
    {
        return $this->render('default/landing.html.twig');
    }
}
