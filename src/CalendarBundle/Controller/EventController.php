<?php

namespace CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use CalendarBundle\Form\EventType;
use CalendarBundle\Model\EventFormData;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    /**
     * The tooltip form and modal form are differents to not duplicate ID, we hav to handle both of them differently.
     * updated : Jimmy Tournemaine <jimmy.tournemaine@yahoo.fr> 21 mai 2016 18:57:29
     * @Route("/new", name="calendar_event_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        /* The new request can only be an AJAX request */
        if (!$request->isXmlHttpRequest()) {
            throw $this->createAccessDeniedException();
        }

        /* Default form */
        $post = $request->request->get('event');
        $eventFormData = new EventFormData();
        $calendarForm = $this->createForm(EventType::class, $eventFormData)->submit($post);

        /* Default form is submitted */
        if ($calendarForm->isSubmitted() && $post) {
            /* Save if valid */
            if ($calendarForm->isValid()){
                return $this->saveNewEvent($eventFormData);
            }
            /* Submit modal form with default form data */
            else {
                $formData = $request->request->get('event');
                unset($formData['_token']);
                $form = $this
                    ->createModalForm($eventFormData)
                    ->submit($formData)
                ;
            }
        }
        /* Modal form is submit */
        else {
            $form = $this->createModalForm($eventFormData)->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                return $this->saveNewEvent($eventFormData);
            }
        }


        return new Response($this->renderView('CalendarBundle:Event:modal.html.twig', array(
            'form' => $form->createView()
        )), 404);
    }

    protected function createModalForm($data)
    {
        return $this->get('form.factory')
            ->createNamedBuilder('modal_event', EventType::class, $data, array('csrf_protection' => false))
            ->getForm()
        ;
    }

    protected function saveNewEvent($eventFormData)
    {
        $event = $eventFormData->getEventEntity();

        $em = $this->getDoctrine()->getManager();
        $em->persist($event);
        $em->flush();

        return new Response();
    }

    /**
     * @Route("/edit", name="calendar_event_edit")
     */
    public function editAction(Request $request)
    {
        return $this->render('CalendarBundle:Event:edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/delete", name="calendar_event_delete")
     */
    public function deleteAction(Request $request)
    {
        return $this->render('CalendarBundle:Event:delete.html.twig', array(
            // ...
        ));
    }

}
