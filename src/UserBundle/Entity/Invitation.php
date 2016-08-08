<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Invitation
 *
 * @ORM\Table(name="invitation")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\InvitationRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"user", "team"})
 */
class Invitation
{
	const USER_CLASS = \UserBundle\Entity\User::class;
	const TEAM_CLASS = \UserBundle\Entity\Team::class;
	
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean 
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;
    
    /**
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Team")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $team;
    
    /**
     * @ORM\Column(name="invited_class", type="string", length=255, nullable=false)
     */
    private $invitedClass;
    
    /**
     * @ORM\Column(name="send_at", type="datetime")
     */
    private $sendAt;
    

    /**
     * @ORM\PrePersist()
     */
    public function setCurrentDateTime()
    {
    	$this->sendAt = new \DateTime();
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
     * Set status
     *
     * @param integer $status
     *
     * @return Invitation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set invitedClass
     *
     * @param string $invitedClass
     *
     * @return Invitation
     */
    public function setInvitedClass($invitedClass)
    {
    	if ($invitedClass != self::USER_CLASS && $invitedClass != self::TEAM_CLASS)
    		throw new \InvalidArgumentException(self::class.'::setInvitedClass() accept '.self::USER_CLASS.' or '.self::TEAM_CLASS.'.');
    	
    	$this->invitedClass = $invitedClass;

        return $this;
    }

    /**
     * Get invitedClass
     *
     * @return string
     */
    public function getInvitedClass()
    {
        return $this->invitedClass;
    }
    
    public function isUserRequest()
    {
    	return $this->invitedClass == self::TEAM_CLASS;
    }
    
    public function isTeamRequest()
    {
    	return $this->invitedClass == self::USER_CLASS;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Invitation
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set team
     *
     * @param \UserBundle\Entity\Team $team
     *
     * @return Invitation
     */
    public function setTeam(\UserBundle\Entity\Team $team)
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
     * Set sendAt
     *
     * @param \DateTime $sendAt
     *
     * @return Invitation
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Get sendAt
     *
     * @return \DateTime
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }
}
