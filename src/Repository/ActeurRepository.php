<?php

namespace App\Repository;

use App\Entity\Acteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Acteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acteur[]    findAll()
 * @method Acteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acteur::class);
    }
    public function findActeurJoinFilm() {
            return $this->createQueryBuilder('a')
                ->join('a.film', 'f')
                ->getQuery()
                ->getResult();
    }

    public function action15Query($titre) {
        return $this->createQueryBuilder('a')
            ->join('a.film', 'f')
            ->where('f.titre = :titre')
            ->setParameter('titre', $titre)
            ->getQuery()
            ->getResult();
    }

    public function findActeurJoinFilmById($id) {
        return $this->createQueryBuilder('a')
            ->join('a.film', 'o')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function action16Query() {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.film', 'f')
            ->groupBy('a')
            ->having('count(f) >= 3')
            ->getQuery()
            ->getResult();
    }

    public function action18Query($id) {
        return $this->createQueryBuilder('a')
            ->select('g.nom, g.id')
            ->join('a.film', 'f')
            ->join('f.genre', 'g')
            ->where('a.id = :id')
            ->groupBy('g.nom')
            ->having('count(g) >= 2')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function action20Query() {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->join('a.film', 'f')
            ->groupBy('a')
            ->orderBy('f.dateSortie', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function action19Query($nom) {
        return $this->createQueryBuilder('a')
            ->join('a.film', 'f')
            ->where('a.nomPrenom = :nom')
            ->setParameter('nom', $nom)
            ->select('a.id,a.nomPrenom,a.dateNaissance,a.nationalite , SUM(f.duree) as sumDuree')
            ->getQuery()
            ->getResult();
    }

    public function findNomActeur($nom)
    {
        return $this->createQueryBuilder('a')
            ->Where('a.nomPrenom = :nom')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getResult();
    }

    public function action21Query($val) {
//        return $this->createQueryBuilder('a')
//            ->join('a.film', 'f')
//            ->join('f.genre', 'g')
//            ->where('f.genre is NOT NULL')
//            ->distinct('g.nom')
//            ->groupBy('a')
//            ->getQuery()
//            ->getResult();
        $data= $this->createQueryBuilder('a')
            ->join('a.film', 'f')
            ->join('f.genre', 'g')
            ->select('g.nom')
            ->where('f.genre is NOT NULL and a.nomPrenom= :nom')
            ->setParameter('nom',$val)
           // ->distinct('g.nom')
           // ->groupBy('a')
            ->getQuery()
            ->getResult();
        $result = array_column($data, 'nom');
        $data = array_unique($result);
        return $data;
    }

    public function action26Query($id) {
        return $this->createQueryBuilder('a')
            ->select('f.id')
            ->join('a.film', 'f')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findActeursByPartOfNom($nom)
    {
        return $this->createQueryBuilder('a')
            ->Where('a.nomPrenom like :nom')
            ->setParameter('nom', $nom.'%')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Acteur[] Returns an array of Acteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Acteur
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
