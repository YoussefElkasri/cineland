<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genre;
use App\Entity\Film;
use App\Entity\Acteur;

class InitController extends AbstractController
{
    public function Insert(){

        $entityManager = $this->getDoctrine()->getManager();


        //Insert into table Genre

        $arr_genre = [
            'animation',
            'policier',
            'drame',
            'comédie',
            'X'
        ];
        foreach ($arr_genre as $nom) {
            $genre = new Genre();
            $genre->setNom($nom);
            $entityManager->persist($genre);
            $entityManager->flush();
        }


        //Insert into table Acteur


        $arr_acteur = [
            ['Galabru Michel', '27/10/1922', 'france'],
            ['Deneuve Catherine', '22/10/1943', 'france'],
            ['Depardieu Gérard', '27/12/1948', 'russie'],
            ['Lanvin Gérard', '21/06/1950', 'france'],
            ['Désiré Dupond', '23/12/2001', 'groland'],
            ['Audrey Fleurot', '06/06/1977', 'france'],
            ['Clotilde Mollet', '18/10/1973', 'france'],
            ['Anne Le Ny', '16/12/1962', 'france'],
            ['François Civil', '29/01/1990', 'france'],
            ['Mathieu Kassovitz', '03/08/1967', 'france'],
            ['Paula Beer', '01/02/1995', 'germany'],
        ];
        foreach ($arr_acteur as list($nomPrenom, $dateNaissance, $nationalite)) {
            $acteur = new Acteur();
            $acteur->setNomPrenom($nomPrenom)
                ->setDateNaissance(\DateTime::createFromFormat('d/m/Y', $dateNaissance))
                ->setNationalite($nationalite);
            $entityManager->persist($acteur);
            $entityManager->flush();
        }

        //insert into table film

        $arr_film = [
            ['Astérix aux jeux olympiques', 117, '20/01/2008', 8, 0, 'animation', []],
            ['Le Dernier Métro', 131, '17/09/1980', 15, 12, 'drame', ['Deneuve Catherine', 'Depardieu Gérard']],
            ['Le choix des armes', 135, '19/10/1981', 13, 18, 'policier', ['Deneuve Catherine', 'Depardieu Gérard', 'Lanvin Gérard']],
            ['Les Parapluies de Cherbourg', 91, '19/02/1964', 9, 0, 'drame', ['Deneuve Catherine']],
            ['The Intouchables', 112, '23/09/2011', 10, 0, 'drame', ['Audrey Fleurot','Clotilde Mollet','Anne Le Ny','Paula Beer','Lanvin Gérard']],
            ['The Wolfs Call', 115, '20/02/2019', 8, 7, 'drame', ['François Civil','Mathieu Kassovitz','Paula Beer','Depardieu Gérard']],
            ['La Guerre des boutons', 90, '18/04/1962', 7, 0, 'comédie', ['Galabru Michel']]
        ];
        foreach ($arr_film as list($titre, $duree, $dateSortie, $note, $ageMinimal, $nomGenre, $nomActeurs)) {
            $film = new Film();
            $film->setTitre($titre)
                ->setDuree($duree)
                ->setDateSortie(\DateTime::createFromFormat('d/m/Y', $dateSortie))
                ->setNote($note)
                ->setAgeMinimal($ageMinimal);
            $genre = $this->getDoctrine()->getRepository(Genre::class)->findOneBy(['nom' => $nomGenre]);
            $film->setGenre($genre);
            foreach ($nomActeurs as $nomPrenom) {
                $acteur = $this->getDoctrine()->getRepository(Acteur::class)->findOneBy(['nomPrenom' => $nomPrenom]);
                $film->addActeur($acteur);
            }
            $entityManager->persist($film);
            $entityManager->flush();

        }


        return $this->redirectToRoute('cineland_accueil');


    }
}
