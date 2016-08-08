<?php

namespace CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CalendarBundle\Model\EventFormData;
use CalendarBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends Controller
{
    /**
     * @Route("/", name="calendar")
     */
    public function calendarAction()
    {
        $eventFormData = new EventFormData();
        $form = $this->createForm(EventType::class, $eventFormData);

        return $this->render('CalendarBundle:Calendar:calendar.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/calendar.ics", name="calendar_ics")
     */
    public function iCalendarAction()
    {
        $user = $this->getUser();
        $startDate = new \DateTime();
        $startDate->setTime(0, 0);

        $em = $this->getDoctrine()->getManager();
        $events = array_merge(
            $em->getRepository("CalendarBundle:Meeting")->findByUserNewerThan($user, $startDate),
            $em->getRepository("CalendarBundle:Rendezvous")->findByUserNewerThan($user, $startDate)
        );

        $response = new Response();
        $response->headers->set('Content-Type', 'text/calendar');

        return $this->render('CalendarBundle:Calendar:events.ics.twig', array('events' => $events), $response);
    }
}
