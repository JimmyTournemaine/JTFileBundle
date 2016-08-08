<?php

namespace TaskBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TaskBundle\Entity\Task;
use TaskBundle\Form\TaskType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Task controller.
 *
 * @Route("/task")
 */
class TaskController extends Controller
{
    /**
     * Lists all Task entities.
     *
     * @Route("/", name="task_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tasks = $em->getRepository('TaskBundle:Task')->findByUser($this->getUser());

        return $this->render('task/index.html.twig', array(
            'tasks' => $tasks,
        ));
    }
    
    /**
     * Update the status of a task
     * 
     * @Route("/update", name="task_update_status", options={"expose"=true})
     * @Method("GET")
     */
    public function updateStatusAction(Request $request)
    {
    	if($request->isXmlHttpRequest()){
    		$id = $request->query->get('id');
    		$em = $this->getDoctrine()->getManager();
    		$task = $em->getRepository('TaskBundle:Task')->find($id);
    		$user = $this->getUser();
    		switch($request->query->get('status')) {
    			case "true":
    				$status = true;
    				break;
    			case "false":
    				$status = false;
    				break;
    			default:
    				$status = null;
    		}
    		
    		if(null == $task)
    			return $this->createNotFoundHttpException();
    		if(!$task->getUsers()->contains($user))
    			return $this->createAccessDeniedException();
    		
    		$task->setStatus($status);
    		$em->flush();
    	}
    	return new Response();
    }

    /**
     * Creates a new Task entity.
     *
     * @Route("/new", name="task_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm('TaskBundle\Form\TaskType', $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_index');
        }
        
        if($request->isXmlHttpRequest())
        	$template = 'task/new_content.html.twig';
        else
        	$template = 'task/new.html.twig';
        
		return $this->render($template, array(
            'task' => $task,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit", name="task_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $task)
    {
    	if (!$task->getUsers()->contains($this->getUser()))
    		return $this->createAccessDeniedHttpException();
    	
        $editForm = $this->createForm('TaskBundle\Form\TaskType', $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_index');
        }
        
        if($request->isXmlHttpRequest())
        	$template = 'task/edit_ajax.html.twig';
        else
        	$template = 'task/edit.html.twig';

        return $this->render($template, array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
        ));
    }
}
