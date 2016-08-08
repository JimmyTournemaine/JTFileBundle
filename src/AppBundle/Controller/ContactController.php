<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use UserBundle\Controller\HavingTeamIsRequiredController;

/**
 * Contact controller.
 *
 * @Route("/contact")
 */
class ContactController extends Controller implements HavingTeamIsRequiredController
{
	const SESSION_IMPORT_KEY = 'imported_contact';
	
    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="contact_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository('AppBundle:Contact')->findAll();

        return $this->render('contact/index.html.twig', array(
            'contacts' => $contacts,
        ));
    }

    /**
     * Creates a new Contact entity.
     *
     * @Route("/new", name="contact_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm('AppBundle\Form\ContactType', $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	$contact->setTeam($this->getUser()->findTeam());
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contact_show', array('id' => $contact->getId()));
        }

        return $this->render('contact/new.html.twig', array(
            'contact' => $contact,
            'form' => $form->createView(),
        	'googleApiKey' => $this->getParameter('google_maps_api_key'),
        ));
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="contact_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Contact $contact)
    {
        $deleteForm = $this->createDeleteForm($contact);
        $editForm = $this->createForm('AppBundle\Form\ContactType', $contact);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contact_edit', array('id' => $contact->getId()));
        }

        return $this->render('contact/edit.html.twig', array(
            'contact' => $contact,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Contact entity.
     *
     * @Route("/{id}", name="contact_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Contact $contact)
    {
        $form = $this->createDeleteForm($contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contact);
            $em->flush();
        }

        return $this->redirectToRoute('contact_index');
    }
    
    /**
     * Import contacts from google account
     * 
     * @Route("/import/{page}", name="contact_import", defaults={"page" = 1}, requirements={"page":"[0-9]+"}, options={"expose":true})
     * @Method({"GET"})
     */
    public function importAction(Request $request, $page = 1)
    {
    	// Only AJAX can access to specific page
    	if (!$request->isXmlHttpRequest() && $page > 1)
    		throw $this->createNotFoundException();
    	
    	$response = $this
    		->get('app.google.contacts.list')
    		->prepare($page)
    		->execute()
    		->getResult()
    	;
    	
    	if ($request->isXmlHttpRequest()){
    		return new JsonResponse(json_encode(array(
    				'html' => $this->renderView('contact/import_list.html.twig', ['contacts' => $response['contacts']]),
    				'hasNext' => $response['hasNext'],
    				'page' => $page,
    		)));
    	}
    	
    	return $this->render('contact/import.html.twig', array(
    			'contacts' => $response['contacts'],
    			'hasNext' => $response['hasNext'],
    			'page' => $page,
    	));
    }
    
    /**
     * Save imported contacts from google account in the session
     *
     * @Route("/import", name="contact_import_save", options={"expose":true})
     * @Method({"POST"})
     */
    public function importSaveDataAction(Request $request)
    {
    	// Only AJAX access
    	if (!$request->isXmlHttpRequest()) {
    		throw $this->createAccessDeniedException();
    	}
    	
    	// Empty value sent
    	$json = $request->request->get('contacts');
    	if ($json == '[]')
    		throw $this->createNotFoundException();
    	
    	// JSON to PHP Objects
    	$contacts = array();
    	$contactsArray = json_decode($json, true);
    	foreach ($contactsArray as $contact){
    		$contacts[] = Contact::fromArray($contact);
    	}
    	
    	// Add to $_SESSION
    	$session = $this->get('session');
    	if($session->has('self::SESSION_IMPORT_KEY')){
    		$previous = $session->get(self::SESSION_IMPORT_KEY);
    		$contacts = array_merge($contacts, $previous);
    	}
    	$session->set(self::SESSION_IMPORT_KEY, $contacts);
    	
    	// Return an url for ajax redirection
    	return new Response($this->generateUrl('contact_import_create'));
    }
    
    /**
     * Use the session to create users
     *
     * @Route("/import/create", name="contact_import_create")
     * @Method({"GET", "POST"})
     */
    public function importCreateAction(Request $request)
    {
    	$session = $this->get('session');
    	$contacts = $session->get(self::SESSION_IMPORT_KEY);
    	
    	// Import is done
    	if (empty($contacts)){
    		$this->addFlash('info', $this->get('translator')->trans('contact.import.create.done'));
    		return $this->redirectToRoute('contact_index');
    	}
    	
    	// Get contact from session and handle form
    	$contact = array_pop($contacts);
    	$form = $this->createForm(ContactType::class, $contact);
    	$form->handleRequest($request);
    	
    	if ($form->isSubmitted() && $form->isValid())
    	{
    		$contact->setTeam($this->getUser()->findTeam());
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($contact);
    		$em->flush();
    		$session->set(self::SESSION_IMPORT_KEY, $contacts);
    		
    		return $this->redirectToRoute('contact_import_create');
    	}
    	
    	return $this->render('contact/import_create.html.twig', array(
    			'form' => $form->createView(),
        		'googleApiKey' => $this->getParameter('google_maps_api_key'),
    	));
    }
    
    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/{id}", name="contact_show")
     * @Method("GET")
     */
    public function showAction(Contact $contact)
    {
    	$deleteForm = $this->createDeleteForm($contact);
    
    	return $this->render('contact/show.html.twig', array(
    			'contact' => $contact,
    			'delete_form' => $deleteForm->createView(),
    	));
    }

    /**
     * Creates a form to delete a Contact entity.
     *
     * @param Contact $contact The Contact entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contact $contact)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_delete', array('id' => $contact->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
