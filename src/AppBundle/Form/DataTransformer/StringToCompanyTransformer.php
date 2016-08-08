<?php
namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Company;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringToCompanyTransformer implements DataTransformerInterface
{
	private $manager;
	
	public function __construct(ObjectManager $manager)
	{
		$this->manager = $manager;
	}
	
	public function transform($company)
	{
		if (null === $company)
			return '';
		
		return $company->getName();
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
	 */
	public function reverseTransform($companyName) 
	{
		if (null === $company = $this->manager->getRepository('AppBundle:Company')->findOneByName($companyName))
			throw new TransformationFailedException();
		
		return $company;
	}
}