<?php
namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	parent::buildForm($builder, $options);
    	$builder->remove('username');
    }
    
    public function getParent(){
    	return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'user_registration';
    }
    
    public function setDefaultOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults(array('data_class' => 'UserBundle\Entity\User'));
    }
}
