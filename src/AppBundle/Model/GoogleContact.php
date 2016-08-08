<?php
namespace AppBundle\Model;

class GoogleContact implements \JsonSerializable {

	protected $googleId;
	protected $givenName;
	protected $familyName;
	protected $email;

	public function __toString()
	{
		if (null != $this->givenName || null != $this->familyName)
			return $this->getFullName();
		if (null != $email = $this->getEmail())
			return $email;
		return 'Unknown';
	}

	public function jsonSerialize()
	{
		return [
				'googleId' => $this->googleId,
				'givenName' => $this->givenName,
				'familyName' => $this->familyName,
				'email' => $this->email,
		];
	}

	static public function fromArray(array $contactArray)
	{
		$contact = new static();
		$contact->setGoogleId($contactArray['googleId']);
		$contact->setGivenName($contactArray['givenName']);
		$contact->setFamilyName($contactArray['familyName']);
		$contact->setEmail($contactArray['email']);

		return $contact;
	}

	public function getFullName()
	{
		return $this->familyName.' '.$this->givenName;
	}

	public function getGoogleId()
	{
		return $this->googleId;
	}

	public function setGoogleId($googleId)
	{
		$this->googleId = $googleId;
		return $this;
	}

	public function getGivenName()
	{
		return $this->givenName;
	}

	public function setGivenName($givenName)
	{
		$this->givenName = ucfirst(strtolower($givenName));
		return $this;
	}

	public function getFamilyName()
	{
		return $this->familyName;
	}

	public function setFamilyName($familyName)
	{
		$this->familyName = ucfirst(strtolower($familyName));
		return $this;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}
}