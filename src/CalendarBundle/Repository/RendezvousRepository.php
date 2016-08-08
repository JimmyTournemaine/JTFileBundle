<?php

namespace CalendarBundle\Repository;

use UserBundle\Entity\User;

/**
 * EventRepository
 *
 * @author Jimmy Tournemaine <jimmy.tournemaine@yahoo.fr>
 */
class RendezvousRepository extends EventRepository
{
/**
	 * Retrieve user's events between two dates
	 * @param User $user
	 * @param \DateTime $start
	 * @param \DateTime $false
	 * @see CalendarBundle\Repository\EventRepository::findByUserBetween()
	 */
	public function findByUserBetween(User $user, \DateTime $start, \DateTime $end)
	{
	    return $this->createQueryBuilder('r')
	       ->where('r.user = :user')
	       ->andWhere('r.start >= :start')
	       ->andWhere('r.end <= :end OR r.end IS NULL')
	       ->setParameters(array('user' => $user, 'start' => $start, 'end' => $end))
	       ->getQuery()
	       ->getResult()
	    ;
	}

	public function findByUserNewerThan(User $user, \DateTime $start)
	{
	    return $this->createQueryBuilder('r')
	       ->where('r.user = :user')
	       ->andWhere('r.start >= :start')
	       ->setParameters(array('user' => $user, 'start' => $start))
	       ->getQuery()
	       ->getResult()
	    ;
	}
}
