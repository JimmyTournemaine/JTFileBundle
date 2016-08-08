<?php
namespace AppBundle\Validator\Constraint;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use AppBundle\Entity\Company;

class TVAValidator extends ConstraintValidator 
{
	/**
	 * Valide un numero TVA en fonction du pays de celui-ci.
	 * Un pays qu'on ne sais pas traiter (en dehors de l'UE) sera considere valide.
	 * 
	 * {@inheritDoc}
	 * @see \Symfony\Component\Validator\ConstraintValidatorInterface::validate()
	 * 
	 */
	public function validate($company, Constraint $constraint) 
	{
		$tva = $company->getTva();
		switch ($company->getCountry())
		{
			case 'DE':
				$res = preg_match('#^DE[0-9]{9}$#', $tva);
				break;
			case 'AT':
				$res = preg_match("#^ATU[0-9]{8}$#", $tva);
				break;
			case 'BE':
				$res = preg_match("#^BE[0-9]{9}$#", $tva);
				break;
			case 'CY':
				$res = preg_match("#^CY[0-9]{8}[A-Za-z0-9]$#", $tva);
				break;
			case 'DK':
				$res = preg_match("#^DK[0-9]{8}$#", $tva);
				break;
			case 'ES':
				$res = preg_match("#^ES[A-Za-z0-9]{9}$#", $tva);
				break;
			case 'EE':
				$res = preg_match("#^EE[0-9]{9}$#", $tva);
				break;
			case 'FI':
				$res = preg_match("#^FI[0-9]{8}$#", $tva);
				break;
			case 'FR':
				$res = preg_match("#^FR.{2}[0-9]{9}$#", $tva);
				break;
			case 'EL':
				$res = preg_match("#^EL[0-9]{9}$#", $tva);
				break;
			case 'HU':
				$res = preg_match("#^HU[0-9]{8}$#", $tva);
				break;
			case 'IE':
				$res = preg_match("#^IE[A-Za-z0-9]{8}$#", $tva);
				break;
			case 'IT':
				$res = preg_match("#^IT[0-9]{11}$#", $tva);
				break;
			case 'LV':
				$res = preg_match("#^LV[0-9]{11}$#", $tva);
				break;
			case 'LT':
				$res = preg_match("#^LT[0-9]{9}$#", $tva) || preg_match("#^LT[0-9]{12}$#", $tva);
				break;
			case 'LU':
				$res = preg_match("#^LU[0-9]{8}$#", $tva);
				break;
			case 'MT':
				$res = preg_match("#^MT[0-9]{8}$#", $tva);
				break;
			case 'NL':
				$res = preg_match("#^NL[A-Za-z0-9]{8}$#", $tva);
				break;
			case 'PL':
				$res = preg_match("#^PL[0-9]{10}$#", $tva);
				break;
			case 'PT':
				$res = preg_match("#^PT[0-9]{9}$#", $tva);
				break;
			case 'SK':
				$res = preg_match("#^SK[0-9]{10}$#", $tva);
				break;
			case 'CZ':
				$res = preg_match("#^CZ[0-9]{8,10}$#", $tva);
				break;
			case 'RO':
				$res = preg_match("#^RO[0-9]{2,10}$#", $tva);
				break;
			case 'GB':
				$res = preg_match("#^GB[A-za-z0-9]{5}$#", $tva) || preg_match("#^GB[A-za-z0-9]{9}$#", $tva) || preg_match("#^GB[A-za-z0-9]{12}$#", $tva);
				break;
			case 'SI':
				$res = preg_match("#^SI[0-9]{8}$#", $tva);
				break;
			case 'SE':
				$res = preg_match("#^SE[0-9]{12}$#", $tva);
				break;
			default:
				$res = true;
		}
		
		if (!$res) {
			$this->context
				->buildViolation($constraint->message)
				->atPath('tva')
				->addViolation();
		}
	}
}