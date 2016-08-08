<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Form\DataTransformer\StringToCompanyTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactType extends AbstractType
{
	private $manager;
	
	public function __construct(ObjectManager $manager)
	{
		$this->manager = $manager;
	}
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('familyName')
            ->add('givenName')
            ->add('email', EmailType::class)
            ->add('company', TextType::class, array(
            		'invalid_message' => 'contact.form.company.invalid_message'
            ))
        ;
        
        $builder->get('company')->addModelTransformer(new StringToCompanyTransformer($this->manager));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact'
        ));
    }
}
