<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Team", inversedBy="members")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $team;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Team", inversedBy="admins")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $teamAdmin;
    
    /**
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    protected $googleId;
    
    /**
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    protected $googleAccessToken;

    public function __construct()
    {
    	parent::__construct();
    }
    
    public function setEmail($email)
    {
    	$email = is_null($email) ? '' : $email;
    	parent::setEmail($email);
    	$this->setUsername($email);
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * Set googleAccessToken
     *
     * @param string $googleAccessToken
     *
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->googleAccessToken = $googleAccessToken;

        return $this;
    }

    /**
     * Get googleAccessToken
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->googleAccessToken;
    }

    /**
     * Set team
     *
     * @param \UserBundle\Entity\Team $team
     *
     * @return User
     */
    public function setTeam(\UserBundle\Entity\Team $team = null)
    {
        $this->team = $team;

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

    /**
     * Set teamAdmin
     *
     * @param \UserBundle\Entity\Team $teamAdmin
     *
     * @return User
     */
    public function setTeamAdmin(\UserBundle\Entity\Team $teamAdmin = null)
    {
        $this->teamAdmin = $teamAdmin;

        return $this;
    }

    /**
     * Get teamAdmin
     *
     * @return \UserBundle\Entity\Team
     */
    public function getTeamAdmin()
    {
        return $this->teamAdmin;
    }
    
    public function isAdmin(\UserBundle\Entity\Team $team)
    {
    	return $this->getTeamAdmin() == $team;
    }
    
    public function hasTeam()
    {
    	return ($this->getTeam() != null || $this->getTeamAdmin() != null);
    }
    
    public function findTeam()
    {
    	if (null != $team = $this->getTeam()){
    		return $team;
    	}
    	if (null != $team = $this->getTeamAdmin()){
    		return $team;
    	}
    	return null;
    }
}
