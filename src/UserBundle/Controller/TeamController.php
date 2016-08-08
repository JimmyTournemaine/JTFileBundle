<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use UserBundle\Entity\Team;
use UserBundle\Form\TeamType;
use UserBundle\Exception\NoTeamException;
use UserBundle\Event\TeamDeletedEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

/**
 * Team controller.
 *
 * @Route("/team")
 */
class TeamController extends Controller
{
	/**
	 * If user does ot have a team
	 *
	 * @Route("/noteam", name="noteam")
	 * @Method("GET")
	 *
	 */
	public function noteamAction()
	{
		return $this->render('team/noteam.html.twig');
	}


    /**
     * Lists all Team entities.
     *
     * @Route("/", name="team_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('UserBundle:Team')->findMyteam($this->getUser());
        if ($team == null) {
        	throw new NoTeamException();
        }

        $invitations = $em->getRepository('UserBundle:Invitation')->findByTeam($team);

        return $this->render('team/index.html.twig', array(
            	'team' => $team,
        		'invitations' => $invitations,
        ));
    }

    /**
     * Creates a new Team entity.
     *
     * @Route("/new", name="team_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
    	/* Already have a team -> redirection */
    	if ($this->getUser()->hasTeam()) {
    		return $this->redirectToRoute('team_index');
    	}

        $team = new Team();
        $form = $this->createForm('UserBundle\Form\TeamType', $team)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $team->addAdmin($this->getUser());
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        }

        // Just content from noteam view
        if ($request->getMethod() == 'GET')
        	$template = 'team/new_content.html.twig';
        // Complete view for post method
        else
        	$template = 'team/new.html.twig';

        return $this->render($template, array(
            'team' => $team,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Team entity.
     *
     * @Route("/{slug}/edit", name="team_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Team $team)
    {
    	/* Only admins are granted */
    	if (!$this->getUser()->isAdmin($team)){
    		throw $this->createAccessDeniedException();
    	}

        $deleteForm = $this->createDeleteForm($team);
        $editForm = $this->createForm('UserBundle\Form\TeamType', $team);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('team/edit.html.twig', array(
            'team' => $team,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Team entity.
     *
     * @Route("/{slug}", name="team_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Team $team)
    {
    	if(!$this->getUser()->isAdmin($team))
    		throw $this->createAccessDeniedException();

        $form = $this->createDeleteForm($team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($team);
            $em->flush();

            $this->get('event_dispatcher')->dispatch(TeamDeletedEvent::NAME, new TeamDeletedEvent($team));
        }

        return $this->redirectToRoute('team_index');
    }

    /**
     * Search a team to join it
     *
     * @Route("/join", name="team_join", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function joinAction(Request $request)
    {
    	$form = $this->createSearchForm();
        $form->handleRequest($request);

        if ($request->getMethod() == 'POST')
        {
        	if ($request->isXmlHttpRequest())
        	{
        		$teams = $this->getDoctrine()->getManager()->getRepository('UserBundle:Team')->findLike($request->request->get('search'));
        		return $this->render('team/join_results_content.html.twig', array(
        				'teams' => $teams
        		));
        	}
        	elseif ($form->isSubmitted() && $form->isValid())
        	{
	        	$data = $form->getData();
	        	$teams = $this->getDoctrine()->getManager()->getRepository('UserBundle:Team')->findLike($data['search']);
	        	return $this->render('team/join_results.html.twig', array(
	        			'teams' => $teams,
	        			'form' => $form->createView(),
	        	));
        	}
        }

        return $this->render('team/join_content.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a Team entity.
     *
     * @param Team $team The Team entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Team $team)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('team_delete', array('slug' => $team->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function createSearchForm()
    {
    	return $this->createFormBuilder()
    		->add('search', SearchType::class, array('label' => 'team.form.search'))
    		->setAction($this->generateUrl('team_join'))
    		->setMethod('POST')
    		->getForm()
    	;
    }
}
