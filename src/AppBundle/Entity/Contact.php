<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Model\GoogleContact;
use AppBundle\AppBundle;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 */
class Contact extends GoogleContact
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="familyName", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $familyName;

    /**
     * @var string
     *
     * @ORM\Column(name="givenName", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $givenName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;
    
    /**
     * @var int
     * 
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    protected $googleId;
    
    /**
     * @var AppBundle\Entity\Company
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;
    
    /**
     * @var UserBundle\Entity\Team
     * 
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getCompany()
    {
    	return $this->company;
    }
    
    public function setCompany(\AppBundle\Entity\Company $company)
    {
    	$this->company = $company;
    }

    /**
     * Set team
     *
     * @param \UserBundle\Entity\Team $team
     *
     * @return Contact
     */
    public function setTeam(\UserBundle\Entity\Team $team)
    {
        $this->team = $team;
        $team->addContact($this);

        return $this;
    }

    /**
     * Get team
     *
     * @return \UserBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }
}
