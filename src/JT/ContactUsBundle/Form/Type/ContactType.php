<?php
namespace JT\ContactUsBundle\Form\Type;

use JT\ContactUsBundle\Form\Subscriber\AddEmailSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    private $subscriber;

    public function __construct(AddEmailSubscriber $subscriber){
        $this->subscriber = $subscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, array(
                'label' => 'contact.labels.subject',
                'translation_domain' => 'JTContactUsBundle'
            ))
            ->add('content', TextareaType::class, array(
                'label' => 'contact.labels.content',
                'translation_domain' => 'JTContactUsBundle'
            ))
            ->addEventSubscriber($this->subscriber);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'JT\ContactUsBundle\Model\Contact'
        ));
    }
}