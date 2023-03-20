<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;

class DataAggregationService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    public function getExpensesAndEarningsForPeriod($from = null, $to = null): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('SUM(ea.amount) as total_earnings')
            ->from('App\Entity\Earnings', 'ea')
            ->where($qb->expr()->between('DATE(ea.createdAt)', ':from', ':to'))
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        $qb->addSelect('(SELECT SUM(ex.amount) FROM App\Entity\Expense ex WHERE DATE(ex.createdAt) BETWEEN :from AND :to) AS total_expenses');

        if (!$from && $to) {
            $qb->setParameter('from', '1970-01-01');
        } elseif ($from && !$to) {
            $qb->setParameter('to', date('Y-m-d'));
        }
        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param $data
     * @param null $period
     * @return array
     */
    public function getExpensesPerCategory($data, $period = null): array
    {
        $now = new \DateTimeImmutable('now');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c.name as category_name, SUM(e.amount) as total_expenses')
            ->from('App\Entity\Expense', 'e')
            ->join('e.category', 'c')
            ->andWhere('e.createdAt <= :now')
            ->setParameter('now', $now)
            ->groupBy('c.id');

        if ($period === 'month') {
            $period = new \DateTimeImmutable('last month');
            $qb->andWhere('e.createdAt >= :period')
                ->setParameter('period', $period);
        } elseif ($period === 'year') {
            $period = new \DateTimeImmutable('last year');
            $qb->andWhere('e.createdAt >= :period')
                ->setParameter('period', $period);
        }

        return $qb->getQuery()->getArrayResult();
    }
}
