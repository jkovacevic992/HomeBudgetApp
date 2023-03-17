<?php

namespace App\Repository;

use App\Entity\Earnings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Earnings>
 *
 * @method Earnings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Earnings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Earnings[]    findAll()
 * @method Earnings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EarningsRepository extends ServiceEntityRepository implements EarningsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Earnings::class);
    }

    public function save(Earnings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Earnings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Earnings[] Returns an array of Earnings objects
     */
    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.id', 'e.amount', 'e.description', 'e.createdAt')
           ->andWhere('e.user = :val')
            ->setParameter('val', $userId)
            ->orderBy('e.id', 'ASC')
           ->getQuery()
           ->getResult();
    }

//    public function findOneBySomeField($value): ?Earnings
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
