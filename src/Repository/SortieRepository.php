<?php

namespace App\Repository;

use App\Data\SortiesCriteria;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    /**
     * @return Sortie[] Returns an array of Sortie objects
     */
    public function findSortiesFiltered(SortiesCriteria $criteria)
    {
        $user = $this->security->getUser();
        $qb=$this->createQueryBuilder("s");
        if($criteria->getSite()!=null){
            $qb->andWhere("s.site = :site")
            ->setParameter("site", $criteria->getSite());
        }
        if($criteria->getSearch()!=""){
            $qb->andWhere($qb->expr()->like('s.name',':name'))
                ->setParameter("name","%".$criteria->getSearch()."%");
        }
        if($criteria->getDateDebut()!=null){
            $qb->andWhere("s.dateTimeStart >= :dateDebut")
                ->setParameter("dateDebut",$criteria->getDateDebut());
        }
        if($criteria->getDateFin()!=null){
            $qb->andWhere("s.dateTimeStart <= :dateFin")
                ->setParameter("dateFin",$criteria->getDateFin());
        }
        if($criteria->isOrganisateur()!=false && $user!=null){
            $qb->andWhere("s.organisateur = :user")
                ->setParameter("user", $user);
        }
        if($criteria->isInscrit()!=false){
            $qb->andWhere("p.user = :user")
                ->setParameter("user", $user);
        }
        if($criteria->isPasInscrit()!=false){
            $qb->andWhere("p.user != :user")
                ->setParameter("user", $user);
        }
        if($criteria->isSortiePassee()!=false){
            $qb->andWhere("s.dateTimeStart < :dateFin")
                ->setParameter("dateFin",new \DateTime());
        }
        $currentDate = new \DateTime();
        $qb->andWhere("s.dateTimeStart >= :dateArchivage")
            ->setParameter("dateArchivage",$currentDate->sub(new \DateInterval('P30D')));
        $qb->andWhere("e.name LIKE :etat OR s.organisateur = :organisateur")
            ->setParameter("etat","Ouverte")
            ->setParameter("organisateur", $user);
        $qb->join("s.etat",'e');
        $qb->Select("s.id, s.name, s.dateTimeStart, s.deadlineRegistration, s.maxNumberRegistration, e.name as etatname, o.id as organisateurid, o.firstname, o.lastname, v.name as villeName, COUNT(p.id) as countedUsers");
        $qb->leftJoin("s.participations", "p");
        $qb->join("s.organisateur", "o");
        $qb->join("s.lieu", "l");
        $qb->join("l.ville", "v");
        $qb->groupBy("s.id");
        return $qb->getQuery()->execute();
    }

    /**
     * @return Sortie[] Returns an array of Sortie objects
     */
    public function findCountParticipants(){
        $qb=$this->createQueryBuilder("s");

    }


}
