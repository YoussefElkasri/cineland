<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Form\Type\GenreType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genre;
use App\Entity\Film;

class GenreController extends AbstractController
{
    //action 1
    public function listeGenre()
    {
        $genres = $this->getDoctrine()
            ->getRepository(Genre::class)->findAll();
        return $this->render('cineland/listeGenre.html.twig',
            array('genres' => $genres));

    }

    //action 2

    public function ajouterGenre(Request $request) {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            try{
                $entityManager->persist($genre);
                $entityManager->flush();
                return $this->redirectToRoute('Liste_Genre');
            } catch (UniqueConstraintViolationException $e) {
                $message = "Le nom que vous avez fourni existe déjà";

                return $this->render('cineland/ajouterGenre.html.twig',
                    array('ajouterGenreForm' => $form->createView(), 'errorMessage' => $message));
            }
        }
        return $this->render('cineland/ajouterGenre.html.twig',
            array('ajouterGenreForm' => $form->createView(), 'errorMessage' => null));
    }

    //Action 22
    public function action22()
    {



            $genres = $this->getDoctrine()
                ->getRepository(Genre::class)->findAll();
            return $this->render('cineland/action22.html.twig',
                array('genres' => $genres));

    }

    //Action 22 suite
        public function action22Suite(Request $request){

            $id = $request->request->get("id");

            $nom = $this->getDoctrine()
                ->getRepository(Genre::class)->find($id);

            $repo = $this->getDoctrine()->getManager()->getRepository(Genre::class);

            $result = $repo->action22Query($id);

            return $this->render('cineland/action22Suite.html.twig',
                array('genre' => $result, 'nom' => $nom->getNom()));
        }

    //Action 24
    public function action24(Request $request){
        $id = $request->query->get("id");

        if ($id == null){
            $entityManager = $this->getDoctrine()->getManager();

            $repoFilm = $this->getDoctrine()->getManager()->getRepository(Film::class);
            $repoGenre = $this->getDoctrine()->getManager()->getRepository(Genre::class);

            $result2 = $repoFilm->action24QueryV1();

            $result = $repoGenre->action24QueryV2($result2);

            return $this->render('cineland/action24.html.twig',
                array('genres' => $result));
        } else {
            $entityManager = $this->getDoctrine()->getManager();
            $genre = $entityManager->getRepository(Genre::class)->find($id);
            $entityManager->remove($genre);
            $entityManager->flush();

            return $this->redirectToRoute('action24');
        }
    }

    //Des fonctions supplémentaires
    public function BarreRechercheGenres(Request $request)
    {
        $nom = $request->request->get("nom");

        $repo = $this->getDoctrine()->getManager()->getRepository(Genre::class);
        $genres = $repo->findGenreByPartOfNom($nom);
        return $this->render('cineland/accueil/barreRechercheGenres.html.twig',
            array('genres' => $genres));

    }

}

