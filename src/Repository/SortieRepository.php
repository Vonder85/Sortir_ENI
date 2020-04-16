<?php

namespace App\Repository;

use App\Data\SortiesCriteria;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @return Sortie[] Returns an array of Sortie objects
     */
    public function findSortiesFiltered(SortiesCriteria $criteria)
    {
        $qb=$this->createQueryBuilder("s");
        if($criteria->getSite()!=null){

        }
        if($criteria->getSearch()!=""){

        }
        if($criteria->getDateDebut()!=null){

        }
        if($criteria->getDateFin()!=null){

        }
        if($criteria->isOrganisateur()!=false){

        }
        if($criteria->isInscrit()!=false){

        }
        if($criteria->isPasInscrit()!=false){

        }
        if($criteria->isSortiePassee()!=false){

        }
        return $qb->getQuery()->execute();
    }


    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
