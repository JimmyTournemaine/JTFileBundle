<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Company;
use AppBundle\Form\CompanyType;
use UserBundle\Controller\HavingTeamIsRequiredController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;

/**
 * Company controller.
 * 
 * The interface HavingTeamIsRequiredController disallows a user that has no team to access this responses.
 * 
 * @Route("/company")
 */
class CompanyController extends Controller implements HavingTeamIsRequiredController
{
    /**
     * Creates a new Company entity.
     *
     * @Route("/new", name="company_new", options={"expose": true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {	
    	// Hydrate data
        $company = new Company();
        $form = $this->createForm('AppBundle\Form\CompanyType', $company);
        $form->handleRequest($request);
        
        // Submit form in Ajax case
		if ($request->isXmlHttpRequest() && $request->isMethod('POST')){
			$form->submit($request->request->all());
		}
		
		// Persist data if ok
        if ($form->isSubmitted() && $form->isValid()) {
        	
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();
            
            if ($request->isXmlHttpRequest())
            	return new Response($company->getName());
            return $this->redirectToRoute('company_show', array('id' => $company->getId()));
        }
        
        $template = ($request->isXmlHttpRequest()) ? 'company/new_content.html.twig' : 'company/new.html.twig';
        $response = $this->render($template, array(
            	'company' => $company,
            	'form' => $form->createView(),
        		'googleApiKey' => $this->getParameter('google_maps_api_key'),
        ));
        
        // Ajax form not validated
        if ($request->isXmlHttpRequest() && $request->isMethod('POST'))
        	$response->setStatusCode(449);
        
        return $response;
    }

    /**
     * Finds and displays a Company entity.
     *
     * @Route("/{id}", name="company_show")
     * @Method("GET")
     */
    public function showAction(Company $company)
    {
        $deleteForm = $this->createDeleteForm($company);

        return $this->render('company/show.html.twig', array(
            'company' => $company,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     * @Route("/{id}/edit", name="company_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Company $company)
    {
        $deleteForm = $this->createDeleteForm($company);
        $editForm = $this->createForm('AppBundle\Form\CompanyType', $company);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('company_edit', array('id' => $company->getId()));
        }

        return $this->render('company/edit.html.twig', array(
            'company' => $company,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Company entity.
     *
     * @Route("/{id}", name="company_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Company $company)
    {
        $form = $this->createDeleteForm($company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($company);
            $em->flush();
        }

        return $this->redirectToRoute('company_index');
    }
    
    /**
     * Get companies name for autocompletion
     * 
     * @Route("/find-by-name/{name}.json", name="company_autocompletion", options={"expose":true})
     * @Method("GET")
     */
    public function autocompletionAction(Request $request, $name)
    {
    	if (!$request->isXmlHttpRequest())
    		throw $this->createAccessDeniedException();
    	
    	$em = $this->getDoctrine()->getManager();
    	$companies = $em->getRepository('AppBundle:Company')->findByNameLike($name);
    	dump($companies);
    	
    	return new Response(json_encode($companies, true));
    }

    /**
     * Creates a form to delete a Company entity.
     *
     * @param Company $company The Company entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Company $company)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('company_delete', array('id' => $company->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
