<?php
namespace UserBundle\Controller;

use HWI\Bundle\OAuthBundle\Controller\ConnectController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseController
{
    /**
     * Login popup
     *
     * @Route("/popover/login", name="login_popover", options={"expose":true})
     * @Method("GET")
     *
     */
    public function loginAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        $response =  parent::connectAction($request);
        $response->setContent($this->renderView('HWIOAuthBundle:Connect:login_content.html.twig'));

        return $response;
    }
}