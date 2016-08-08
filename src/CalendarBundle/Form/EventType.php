<?php
namespace CalendarBundle\Form;

use CalendarBundle\Model\EventFormData;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class EventType extends AbstractType
{

    private $token;

    public function __construct(TokenStorage $token)
    {
        $this->token = $token;
    }

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->token->getToken()->getUser();

        $builder->add('title')
            ->add('description')
            ->add('allDay', CheckboxType::class)
            ->add('date', DateType::class, array(
            'widget' => 'single_text'
        ))
            ->add('start', DateTimeType::class, array(
            'date_widget' => 'single_text',
            'time_widget' => 'choice'
        ))
            ->add('end', DateTimeType::class, array(
            'date_widget' => 'single_text',
            'time_widget' => 'choice'
        ))
            ->add('type', ChoiceType::class, array(
            'choices' => EventFormData::types()
        ))
            ->add('owner', HiddenType::class, array(
            'data' => $user
        ))
            ->add('contact', EntityType::class, array(
            'class' => 'AppBundle:Contact',
            'choice_label' => 'fullname',
            'query_builder' => function (EntityRepository $er) use ($user) {
                return $er->createContactByTeamQueryBuilder($user->findTeam())
                    ->orderBy('c.givenName')
                    ->orderBy('c.familyName');
            }
        ))
            ->add('users', EntityType::class, array(
            'class' => 'UserBundle:User',
            'choice_label' => 'username',
            'query_builder' => function (EntityRepository $er) use ($user) {
                return $er->createAllMyTeamQueryBuilder($user)
                    ->orderBy('u.username');
            },
            'multiple' => true
        ));

        $builder->get('owner')->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($user) {
            $event->setData($user);
        });
    }

    /**
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CalendarBundle\Model\EventFormData'
        ));
    }
}
