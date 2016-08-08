<?php

namespace UserBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\Invitation;
use UserBundle\Entity\Team;
use UserBundle\Entity\User;
use UserBundle\Event\InvitationAcceptedEvent;
use UserBundle\Event\InvitationSentEvent;
use UserBundle\Event\InvitationToJoinEvent;
use UserBundle\Exception\LastAdminException;
use UserBundle\Exception\UserNotFoundException;

/**
 * Invitation controller.
 *
 * @Route("/invitation")
 */
class InvitationController extends Controller
{
    /**
     * Lists all Invitation entities.
     *
     * @Route("/", name="invitation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $invitations = $em->getRepository('UserBundle:Invitation')->findBy(array('user' => $this->getUser()));
        
        return $this->render('invitation/index.html.twig', array(
            'invitations' => $invitations,
        ));
    }

    /**
     * Creates a new Invitation entity.
     *
     * @Route("/team/{slug}", name="invitation_by_user")
     * @Method({"GET", "POST"})
     */
    public function byUserAction(Request $request, Team $team)
    {
        $invitation = new Invitation();
        
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $invitation->setTeam($team);
            $invitation->setUser($this->getUser());
            $invitation->setInvitedClass(Team::class);
            
            $em->persist($invitation);
            $em->flush();
            
            $this->get('event_dispatcher')->dispatch(InvitationSentEvent::NAME, new InvitationSentEvent($invitation));

            return $this->redirectToRoute('invitation_index');
        }

        return $this->render('invitation/by_user.html.twig', array(
            'invitation' => $invitation,
            'form' => $form->createView(),
        ));
    }
    
	/**
     * Creates a new Invitation entity fron a team to an user.
     *
     * @Route("/invite", name="invitation_by_team", options={"expose": true})
     * @Method({"GET", "POST"})
     */
    public function byTeamAction(Request $request)
    {
    	// Only admin can invite a user
    	if (null == $team = $this->getUser()->getTeamAdmin())
    		throw $this->createAccessDeniedException();
    	
    	// Create form
        $invitation = new Invitation();
        $invitation->setInvitedClass(User::class);
        $form = $this
        	->createFormBuilder()
        	->add('username')
        	->getForm()
        	->handleRequest($request)
        ;
        
        if ($request->getMethod() == 'GET') {
        	return $this->render('invitation/by_team_content.html.twig', array(
        			'invitation' => $invitation,
        			'form' => $form->createView(),
        	));
        }
    	
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("UserBundle:User");
        $error = "";
        $translator = $this->get('translator');
        
        try {
        	/* Search user by username */
        	$username = ($request->isXmlHttpRequest()) ? $request->request->get('username') : $form->getData()['username'];
        	$user = $repository->findOneByUsernameOrEmail($username);
        	if ($user == null){
        		throw new UserNotFoundException($username);
        	}
        	
        	/* Record the invitation */
        	$invitation->setUser($user);
        	$invitation->setTeam($this->getUser()->getTeamAdmin());
        	$em->persist($invitation);
        	$em->flush();
        	$this->get('event_dispatcher')->dispatch(InvitationSentEvent::NAME, new InvitationSentEvent($invitation));
        	
        } catch (UserNotFoundException $e) {
        	if (!filter_var($username, FILTER_VALIDATE_EMAIL) === false){
        		$event = new InvitationToJoinEvent($this->getUser(), $this->getUser()->getTeamAdmin(), $username);
        		$this->get('event_dispatcher')->dispatch(InvitationToJoinEvent::NAME, $event);
        		$error = $translator->trans('invitation.form.error.user_not_found_but_join');
        	} else {
        		$error = $translator->trans('invitation.form.error.user_not_found');
        	}
        } catch (UniqueConstraintViolationException $e) {
        	$error = $translator->trans('invitation.form.error.already_exists');
        }
        
        // Ajax Response
        if ($request->isXmlHttpRequest()) {
        	if ($error) {
        		return new Response($error, 404);
        	}
        	
        	$em->refresh($invitation);
	    	return $this->render('invitation/one_line.html.twig', array(
	    			'invitation' => $invitation,
	    	));
        }
        
        // "Normal" Response
        if ($error) {
        	$form->addError(new FormError($error));
        	return $this->render('invitation/by_team.html.twig', array(
        			'form' => $form->createView(),
        			'invitation' => $invitation,
        	));
        }
        
        $this->addFlash('success', $translator->trans('invitation.by_team.flash.success'));
        return $this->redirectToRoute('team_index');
    }
    
    
    /**
     * Accept an invitation
     * 
     * @Route("/accept/{id}", name="invitation_accept")
     * @Method({"GET", "POST"})
     * 
     */
    public function accept(Request $request, Invitation $invitation)
    {
    	/* Error handling */
    	if ($invitation->getInvitedClass() == Invitation::USER_CLASS){
    		// Only concerned user can accept an invitation from a team
    		if ($invitation->getUser() != $this->getUser())
    			throw $this->createAccessDeniedException();
    		
    		// Last admin of a team cannot accept an invitation
    		if (null != $currentTeam = $invitation->getUser()->getTeamAdmin()){
    			if ($currentTeam->getAdmins()->count() <= 1)
    				throw new LastAdminException();
    		}
    	}
    	elseif ($invitation->getInvitedClass() == Invitation::TEAM_CLASS){
    		// Only admin can accept an invitation from a user
    		if (!$this->getUser()->isAdmin($invitation->getTeam()))
    			throw $this->createAccessDeniedException();
    	}
    		
    	
    	$form = $this->createFormBuilder()->getForm()->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()){
    		$em = $this->getDoctrine()->getManager();
    		$invitation->setStatus(true);
    		$invitation->getTeam()->addMember($invitation->getUser());
    		
    		$em->flush();
    		
    		$this->get('event_dispatcher')->dispatch(InvitationAcceptedEvent::NAME, new InvitationAcceptedEvent($invitation));
    		
    		return $this->redirectToRoute('team_index');
    	}
    	
    	return $this->render('invitation/accept.html.twig', array(
    			'invitation' => $invitation,
    			'form' => $form->createView(),
    	));
    }
    
    /**
     * Decline an invitation
     *
     * @Route("/decline/{id}", name="invitation_decline")
     * @Method({"GET", "POST"})
     *
     */
    public function decline(Request $request, Invitation $invitation)
    {
    	// Only concerned user can accept an invitation from a team
    	if ($invitation->getInvitedClass() == Invitation::USER_CLASS){
    		if ($invitation->getUser() != $this->getUser())
    			throw $this->createAccessDeniedException();
    	}
    	// Only admin can accept an invitation from a user
    	elseif ($invitation->getInvitedClass() == Invitation::TEAM_CLASS){
    		if (!$this->getUser()->isAdmin($invitation->getTeam()))
    			throw $this->createAccessDeniedException();
    	}
    	 
    	$form = $this->createFormBuilder()->getForm()->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()){
    		$em = $this->getDoctrine()->getManager();
    		$invitation->setStatus(false);
    		$em->flush();
    
    		return $this->redirectToRoute('team_index');
    	}
    	 
    	return $this->render('invitation/decline.html.twig', array(
    			'invitation' => $invitation,
    			'form' => $form->createView(),
    	));
    }

    /**
     * Deletes a Invitation entity.
     *
     * @Route("/delete/{id}", name="invitation_delete")
     * @Method({"GET","DELETE"})
     */
    public function deleteAction(Request $request, Invitation $invitation)
    {
    	$backRoute = ($invitation->isUserRequest()) ? 'invitation_index' : 'team_index';
    	
        $form = $this->createDeleteForm($invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($invitation);
            $em->flush();
            
            return $this->redirectToRoute($backRoute);
        }

        return $this->render('invitation/delete.html.twig', array(
        		'form' => $form->createView(),
        		'backRoute' => $backRoute
        ));
    }

    /**
     * Creates a form to delete a Invitation entity.
     *
     * @param Invitation $invitation The Invitation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Invitation $invitation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('invitation_delete', array('id' => $invitation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
