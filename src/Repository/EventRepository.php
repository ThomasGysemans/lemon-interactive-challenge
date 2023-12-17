<?php

namespace App\Repository;

use DateTime;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Finds the events in between two specific dates.
     */
    public function findBetweenDates(DateTime $begin, DateTime $end, int $offset, int $max, ?int $userId): array
    {
        if (is_null($userId)) {
            return [
                "count" => $this->createQueryBuilder('e')
                    ->select("count(e.id)")
                    ->andWhere('e.beginDate >= :begin')
                    ->andWhere('e.endDate <= :end')
                    ->setParameter('begin', $begin)
                    ->setParameter('end', $end)
                    ->getQuery()
                    ->getSingleScalarResult(),
                "events" => 
                    $this->createQueryBuilder('e')
                        ->andWhere('e.beginDate >= :begin')
                        ->andWhere('e.endDate <= :end')
                        ->setParameter('begin', $begin)
                        ->setParameter('end', $end)
                        ->orderBy('e.beginDate', 'ASC')
                        ->setFirstResult($offset)
                        ->setMaxResults($max)
                        ->getQuery()
                        ->getResult()
            ];
        } else {
            return [
                "count" => $this->joinsUserEvents(
                    $userId,
                    $this->createQueryBuilder('e')
                    ->select("count(e.id)")
                    ->andWhere('e.beginDate >= :begin')
                    ->andWhere('e.endDate <= :end')
                    ->setParameter('begin', $begin)
                    ->setParameter('end', $end)
                )->getSingleScalarResult(),
                "events" => $this->joinsUserEvents(
                    $userId,
                    $this->createQueryBuilder('e')
                    ->andWhere('e.beginDate >= :begin')
                    ->andWhere('e.endDate <= :end')
                    ->setParameter('begin', $begin)
                    ->setParameter('end', $end)
                    ->orderBy('e.beginDate', 'ASC')
                    ->setFirstResult($offset)
                    ->setMaxResults($max)
                )->getResult()
            ];
        }
    }

    /**
     * Finds the events after a specific date.
     */
    public function findAfterDate(DateTime $begin, int $offset, int $max, ?int $userId): array
    {
        if (is_null($userId)) {
            return [
                "count" => $this->createQueryBuilder('e')
                    ->select("count(e.id)")
                    ->andWhere('e.beginDate >= :begin')
                    ->setParameter('begin', $begin)
                    ->getQuery()
                    ->getSingleScalarResult(),
                "events" => $this->createQueryBuilder('e')
                    ->andWhere('e.beginDate >= :begin')
                    ->andWhere($this->optionalUser($userId))
                    ->setParameter('begin', $begin)
                    ->orderBy('e.beginDate', 'ASC')
                    ->setFirstResult($offset)
                    ->setMaxResults($max)
                    ->getQuery()
                    ->getResult()
            ];
        } else {
            return [
                "count" => $this->joinsUserEvents(
                    $userId,
                    $this->createQueryBuilder('e')
                    ->select("count(e.id)")
                    ->andWhere('e.beginDate >= :begin')
                    ->setParameter('begin', $begin)
                )->getSingleScalarResult(),
                "events" => $this->joinsUserEvents(
                    $userId,
                    $this->createQueryBuilder('e')
                    ->andWhere('e.beginDate >= :begin')
                    ->andWhere($this->optionalUser($userId))
                    ->setParameter('begin', $begin)
                    ->orderBy('e.beginDate', 'ASC')
                    ->setFirstResult($offset)
                    ->setMaxResults($max)
                )->getResult()
            ];
        }
    }

    /**
     * Finds the events before a specific date.
     */
    public function findBeforeDate(DateTime $end, int $offset, int $max, ?int $userId): array
    {
        if (is_null($userId)) {
            return [
                "count" => $this->createQueryBuilder('e')
                    ->select("count(e.id)")
                    ->andWhere('e.endDate <= :end')
                    ->setParameter('end', $end)
                    ->getQuery()
                    ->getSingleScalarResult(),
                "events" => $this->createQueryBuilder('e')
                    ->andWhere('e.endDate <= :end')
                    ->setParameter('end', $end)
                    ->orderBy('e.endDate', 'DESC')
                    ->setFirstResult($offset)
                    ->setMaxResults($max)
                    ->getQuery()
                    ->getResult()
            ];
        } else {
            return [
                "count" => $this->joinsUserEvents(
                    $userId,
                    $this->createQueryBuilder('e')
                    ->select("count(e.id)")
                    ->andWhere('e.endDate <= :end')
                    ->setParameter('end', $end)
                )->getSingleScalarResult(),
                "events" => $this->joinsUserEvents(
                    $userId,
                    $this->createQueryBuilder('e')
                    ->andWhere('e.endDate <= :end')
                    ->setParameter('end', $end)
                    ->orderBy('e.endDate', 'DESC')
                    ->setFirstResult($offset)
                    ->setMaxResults($max)
                )->getResult()
            ];
        }
    }

    /**
     * Finds and orders the events between $offset and $max.
     */
    public function findPage(int $offset, int $max, ?int $userId): array
    {
        if (is_null($userId)) {
            return [
                "count" => $this->createQueryBuilder('e')
                    ->select("count(e.id)")
                    ->getQuery()
                    ->getSingleScalarResult(),
                "events" => $this->createQueryBuilder('e')
                    ->orderBy('e.beginDate', 'ASC')
                    ->setFirstResult($offset)
                    ->setMaxResults($max)
                    ->getQuery()
                    ->getResult()
            ];
        } else {
            return [
                "count" => $this->joinsUserEvents(
                    $userId,
                    $this->createQueryBuilder('e')
                    ->select("count(e.id)")
                )->getSingleScalarResult(),
                "events" => $this->joinsUserEvents(
                    $userId,
                    $this->createQueryBuilder('e')
                    ->orderBy('e.beginDate', 'ASC')
                    ->setFirstResult($offset)
                    ->setMaxResults($max)
                )->getResult()
            ];
        }
    }

    private function joinsUserEvents(int $userId, QueryBuilder $q): Query
    {
        return $q
            ->innerJoin('e.subscribers', 's', Expr\Join::WITH, "s.id = $userId")
            ->getQuery();
    }
}
