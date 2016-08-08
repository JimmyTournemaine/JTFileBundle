<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchiveController extends Controller
{
    /**
     * @Route("/retrieve", name="archive_retrieve")
     * @Method("GET")
     */
    public function retrieveAction(Request $request)
    {
    	$archive = $this->container->get('app.archive');
    	$content = $archive->getHtml($request->query->get('archive'));
    	if ($content == null) {
    		throw $this->createNotFoundException();
    	}
    	
        return new Response($content);
    }

}
