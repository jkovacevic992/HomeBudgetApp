<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expense>
 *
 * @method Expense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expense[]    findAll()
 * @method Expense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseRepository extends ServiceEntityRepository implements ExpenseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function save(Expense $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Expense $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int $userId
     * @return array
     */
    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :val')
            ->setParameter('val', $userId)
            ->leftJoin('e.category', 'c')
            ->addSelect('c')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param int $userId
     * @param int|null $category
     * @param float|null $min
     * @param float|null $max
     * @param string|null $date
     * @return array
     */
    public function findExpenses(int $userId, int $category = null, float $min = null, float $max = null, string $date = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.category', 'c')
            ->addSelect('c')
            ->andWhere('e.user = :user')
            ->setParameter('user', $userId);

        if ($category) {
            $qb->andWhere('e.category = :category')
                ->setParameter('category', $category);
        }

        if ($date) {
            $qb->andWhere('DATE(e.createdAt) = :date')
                ->setParameter('date', $date);
        }

        if ($min) {
            $qb->andWhere('e.amount > :min')
                ->setParameter('min', $min);
        }

        if ($max) {
            $qb->andWhere('e.amount < :max')
                ->setParameter('max', $max);
        }



        return $qb->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
