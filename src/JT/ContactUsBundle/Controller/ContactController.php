<?php

namespace JT\ContactUsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JT\ContactUsBundle\Model\Contact;
use Symfony\Component\HttpFoundation\Request;
use JT\ContactUsBundle\Form\Type\ContactType;

class ContactController extends Controller
{

    public function showAction(Request $request)
    {
        $contact = new Contact();

        $form = $this
            ->createForm(ContactType::class, $contact)
            ->handleRequest($request)
        ;

        if ($form->isSubmitted() && $form->isValid()) {
            dump($contact);
        }

        return $this->render('JTContactUsBundle:Contact:show.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
