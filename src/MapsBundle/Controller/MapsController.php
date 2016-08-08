<?php

namespace MapsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

class MapsController extends Controller
{
    /**
     * @Route("/maps", name="maps_index")
     */
    public function indexAction()
    {
        $token = $this->get('security.token_storage')->getToken();
        $signIn = ($token instanceof OAuthToken && $token->getResourceOwnerName() == "google") ? 'true' : 'false';
        return $this->render('MapsBundle:Maps:index.html.twig', array(
            'sign_in' => $signIn
        ));
    }

}
