<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function findGenreByPartOfNom($nom)
    {
        return $this->createQueryBuilder('g')
            ->Where('g.nom like :nom')
            ->setParameter('nom', $nom.'%')
            ->getQuery()
            ->getResult();
    }

//    public function action21Query2($result) {
//        return $this->createQueryBuilder('g')
//            ->select('g.nom,a.id,a.nomPrenom,a.nationalite,a.dateNaissance')
//            ->join('g.films', 'f')
//            ->join('f.acteurs', 'a')
//            ->groupBy('g.nom')
//            ->having('f in (:r)')
//            ->setParameter('r', $result)
//            ->getQuery()
//            ->getResult();
//    }

    public function action22Query($id) {
        return $this->createQueryBuilder('g')
            ->select('g.nom AS nomGenre, avg(f.duree) AS moyenne, sum(f.duree) AS somme')
            ->join('g.films', 'f')
            ->groupBy('g')
            ->having('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
    public function action24QueryV2($result2) {
        return $this->createQueryBuilder('g')
            ->select('g.nom, g.id')
            ->where('g NOT IN ( :r )')
            ->setParameter('r', $result2)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Genre[] Returns an array of Genre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Genre
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
