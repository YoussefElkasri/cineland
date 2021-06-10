<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }
    public function action13Query($a,$b) {

        $queryBuilder = $this->createQueryBuilder('f');
        $queryBuilder->where('YEAR(f.dateSortie) between :date1 and :date2')
            ->setParameter('date1', $a)
             ->setParameter('date2', $b);


            return $queryBuilder->getQuery()->getResult();

    }

    public function action14Query($date) {
        return $this->createQueryBuilder('f')
            ->select('f')
           // ->join('f.genre', 'g')
            ->where('f.dateSortie < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    public function action17Query($a,$b) {

        return $this->createQueryBuilder('f')
            ->join('f.acteurs', 'a1')
            ->join('f.acteurs', 'a2')
            ->where('a1.nomPrenom like :nom1 and a2.nomPrenom like :nom2')
            ->setParameter('nom1', $a)
            ->setParameter('nom2', $b)
            ->getQuery()
            ->getResult();

    }

    public function action24QueryV1() {
        return $this->createQueryBuilder('f')
            ->select('identity(f.genre)')
            ->getQuery()
            ->getResult();
    }


    public function action25Query($titre) {

        $queryBuilder = $this->createQueryBuilder('f');
        $queryBuilder->where('f.titre like :titre')
            ->setParameter('titre', '%'.$titre.'%');


        return $queryBuilder->getQuery()->getResult();

    }

    public function action23QueryV2($titre) {

        $queryBuilder = $this->createQueryBuilder('f');
        $queryBuilder->where('f.titre like :titre')
            ->setParameter('titre', $titre);


        return $queryBuilder->getQuery()->getResult();

    }

    public function findFilmByActeur($nom) {

        return $this->createQueryBuilder('f')
            ->join('f.acteurs', 'a')
            ->join('f.genre', 'g')
            ->where('a.nomPrenom like :nom')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getResult();

    }
    public function findFilmByGenre($nom) {

        return $this->createQueryBuilder('f')
            ->join('f.acteurs', 'a')
            ->join('f.genre', 'g')
            ->where('g.nom like :nom')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getResult();

    }

    public function findFilmsByPartOfTitre($titre) {

        $queryBuilder = $this->createQueryBuilder('f');
        $queryBuilder->where('f.titre like :titre')
            ->setParameter('titre', $titre.'%');


        return $queryBuilder->getQuery()->getResult();

    }

    // /**
    //  * @return Film[] Returns an array of Film objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
