<?php
namespace JT\ContactUsBundle\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class AddPropertiesSubscriber implements EventSubscriberInterface
{
    private $email = null;

    public function __construct(TokenStorage $tokenStorage, AuthorizationChecker $checker, $emailProperty)
    {
        if ($checker->isGranted('IS_AUTHENTICATED_REMEMBERED') && $emailProperty){
            $user = $tokenStorage->getToken()->getUser();
            $getter = 'get'.ucfirst($emailProperty);
            if (is_callable(array($user, $getter))){
                $this->email = $user->$getter();
            } elseif (is_callable(array($user, $emailProperty))){
                $this->email = $user->$emailProperty();
            } else {
                throw new \LogicException('Cannot call '.$emailProperty.'() or '. $getter . '() for ' . get_class($user) . 'object.');
            }
        }
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Symfony\Component\EventDispatcher\EventSubscriberInterface::getSubscribedEvents()
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_SET_DATA => 'onPreSetData',
            FormEvents::PRE_SUBMIT => 'onPreSubmit'
        );
    }


    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();

        if (!$this->email){
            $form->add('email', EmailType::class, array(
                'label' => 'contact.labels.email',
                'translation_domain' => 'JTContactUsBundle'
            ));
        }
    }

    public function onPreSubmit(FormEvent $event)
    {
        if (!$this->email){
            return;
        }

        $data = $event->getData();
        $form = $event->getForm();
        $form->add('email', null);
        $data['email'] = $this->email;
        $event->setData($data);
    }
}

