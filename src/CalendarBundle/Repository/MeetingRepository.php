<?php
namespace CalendarBundle\Repository;

use UserBundle\Entity\User;

/**
 *
 * @author jimmytournemaine
 */
class MeetingRepository extends EventRepository
{

    /**
     * Retrieve user's events between two dates
     *
     * @param User $user
     * @param \DateTime $start
     * @param \DateTime $false
     * @see CalendarBundle\Repository\EventRepository::findByUserBetween()
     */
    public function findByUserBetween(User $user, \DateTime $start, \DateTime $end)
    {
        return $this->createFindByUserQueryBuilder($user)
            ->andWhere('m.start >= :start')
            ->andWhere('m.end <= :end OR m.end IS NULL')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \CalendarBundle\Repository\EventRepository::findByUserNewerThan()
     */
    public function findByUserNewerThan(User $user, \DateTime $start)
    {
        return $this->createFindByUserQueryBuilder($user)
            ->andWhere('m.start >= :start')
            ->setParameter('start', $start)
            ->getQuery()
            ->getResult()
        ;
    }

    public function createFindByUserQueryBuilder(User $user)
    {
        return $this->createQueryBuilder('m')
            ->join('m.users', 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
        ;
    }
}
