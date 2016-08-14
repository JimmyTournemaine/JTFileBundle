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
    public function indexAction()
    {
        if(!$this->isGranted('IS_AUTHENTICATED_REMEMBERED', $this->getUser())){
            return $this->redirectToRoute('welcome');
        }

        return $this->render('default/homepage.html.twig');
    }

    /**
     * @Route("/welcome", name="welcome")
     */
    public function landingAction()
    {
        return $this->render('default/landing.html.twig');
    }

    /**
     *  @Route("/legal", name="legal")
     */
    public function legalAction(Request $request)
    {
        $filename = $this->getParameter('kernel.root_dir').'/Resources/translations/legal.'.$request->getLocale().'.yml';
        if(!file_exists($filename)){
            $filename = $this->getParameter('kernel.root_dir').'/Resources/translations/legal.'.$this->getParameter('kernel.default_locale').'.yml';
            if (!file_exists($filename)){
                throw new \LogicException('You must define at least a translation file for the default locale at ' . $filename . '.');
            }
        }

        return $this->render('default/privacy.html.twig', array(
           'last_update' => filemtime($filename),
           'project_name' => $this->getParameter('project_name'),
           'project_url' => $this->getParameter('project_url'),
           'project_author' => $this->getParameter('project_author'),
           'project_address' => $this->getParameter('project_address'),
           'project_contact_mail' => $this->getParameter('project_contact_mail'),
           'project_publication_director' => $this->getParameter('project_publication_director'),
           'project_hostname' => $this->getParameter('project_hostname'),
           'project_hostaddr' => $this->getParameter('project_hostaddr'),
           'project_cnil_number' => $this->getParameter('project_cnil_number')
        ));
    }
}
