<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneByEmail($email){
        $qb = $this->createQueryBuilder('u');
        $qb->andWhere('u.email = :email')->setParameter('email', $email);
        $query = $qb->getQuery();
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    public function findOneByToken($token){
        $qb = $this->createQueryBuilder('u');
        $qb->andWhere('u.resetToken = :token')->setParameter('token', $token);
        $query = $qb->getQuery();
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    /**
     * @param $sortie
     * @return array
     */
    public function findAllBySortie($sortie)
    {
        $qb = $this->createQueryBuilder('u')
            -> innerJoin('App\Entity\Participations', 'p', Join::WITH, 'u.id = p.user')
            -> andWhere ('p.sortie = :sortie')
            ->setParameter('sortie', $sortie);

        return $qb->getQuery()->getArrayResult();

    }

    public function findByUsername($username)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->andWhere('u.username = :username')->setParameter('username', $username);
        $query = $qb->getQuery();
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }
    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
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
