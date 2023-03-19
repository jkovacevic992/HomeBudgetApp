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

}