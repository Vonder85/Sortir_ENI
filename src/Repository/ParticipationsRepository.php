<?php

namespace App\Repository;

use App\Entity\Participations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participations[]    findAll()
 * @method Participations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participations::class);
    }

    /**
     * get nbr de participations pour une sortie
     */
    public function findNbParticipations($idSortie){
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.sortie = :idSortie')->setParameter('idSortie', $idSortie);
        $query = $qb->getQuery();
        return $query->getArrayResult();

    }
    // /**
    //  * @return Etat[] Returns an array of Etat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Etat
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

