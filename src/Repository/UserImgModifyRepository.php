<?php

namespace App\Repository;

use App\Entity\UserImgModify;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserImgModify|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserImgModify|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserImgModify[]    findAll()
 * @method UserImgModify[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserImgModifyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserImgModify::class);
    }

    // /**
    //  * @return UserImgModify[] Returns an array of UserImgModify objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserImgModify
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
