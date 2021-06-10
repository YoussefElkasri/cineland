<?php

namespace App\Controller;

use App\Entity\Acteur;
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
use App\Form\Type\ActeurType;
use App\Form\Type\FilmType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genre;
use App\Entity\Film;

class FilmController extends AbstractController
{
    //Action 8
    public function listeFilm()
    {
        $films = $this->getDoctrine()
            ->getRepository(Film::class)->findAll();
        return $this->render('cineland/listeFilm.html.twig',
            array('films' => $films));

    }


    //Action 9
    public function listeFilmForDetails()
    {
        $films = $this->getDoctrine()
            ->getRepository(Film::class)->findAll();
        return $this->render('cineland/listeFilmForDetailsFilm.html.twig',
            array('films' => $films));

    }

    //Action 9 Suite
    public function detailFilm($id) {

        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);

        if(!$film)
            throw $this->createNotFoundException('Film[id='.$id.'] inexistante');
        return $this->render('cineland/detailFilm.html.twig',
            array('film' => $film));
    }

    //Action 9 Suite v2
    public function detailFilmV2(Request $request) {
        $id = $request->request->get("id");
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);

        if(!$film)
            throw $this->createNotFoundException('Film[id='.$id.'] inexistante');
        return $this->render('cineland/detailFilm.html.twig',
            array('film' => $film));
    }

    //Action 10
    public function ajouterFilm(Request $request) {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            try {
                $entityManager->persist($film);
                $entityManager->flush();
                return $this->redirectToRoute('liste_film');
            }catch(UniqueConstraintViolationException $e){
                $message = "Le titre que vous avez fourni existe déjà";

                return $this->render('cineland/ajouterFilm.html.twig',
                    array('monFormulaire' => $form->createView(), 'errorMessage' => $message));
            }
        }
        return $this->render('cineland/ajouterFilm.html.twig',
            array('monFormulaire' => $form->createView(),'errorMessage' => null));
    }

    //Action 12
    public function supprimerFilm(Request $request){
        $id = $request->query->get("id");
        if ($id == null){
            $films = $this->getDoctrine()->getRepository(Film::class)->findAll();
            return $this->render('cineland/action12GetFilm.html.twig', array('films' => $films));
        } else {
            $entityManager = $this->getDoctrine()->getManager();

            if(!$id)
            {
                throw $this->createNotFoundException('No ID found');
            }

            $film = $entityManager->getRepository(Film::class)->find($id);

            if($film != null)
            {
                $entityManager->remove($film);
                $entityManager->flush();
            }

            return $this->redirectToRoute('liste_film');
        }
    }

    //Action 11
    public function listeFilmForModification()
    {
        $films = $this->getDoctrine()
            ->getRepository(Film::class)->findAll();
        return $this->render('cineland/listeFilmForModificationFilm.html.twig',
            array('films' => $films));

    }

    //Action 11
    public function modifierFilm($id) {

        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);
        if(!$film)
            throw $this->createNotFoundException('Film[id='.$id.'] inexistante');
        $form = $this->createForm(FilmType::class, $film,
            ['action' => $this->generateUrl('modification_film_suite',
                array('id' => $film->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render('cineland/modifierFilm.html.twig',
            array('monFormulaire' => $form->createView(),'errorMessage' => null));
    }

    //Action 11 v2
    public function modifierFilmV2(Request $request) {
        $id = $request->request->get("id");
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);
        if(!$film)
            throw $this->createNotFoundException('Film[id='.$id.'] inexistante');
        $form = $this->createForm(FilmType::class, $film,
            ['action' => $this->generateUrl('modification_film_suite',
                array('id' => $film->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render('cineland/modifierFilm.html.twig',
            array('monFormulaire' => $form->createView(),'errorMessage' => null));
    }

    //Action 11 Suite
    public function modifierFilmSuite(Request $request, $id) {

        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);
        if(!$film)
            throw $this->createNotFoundException('Film[id='.$id.'] inexistante');
        $form = $this->createForm(FilmType::class, $film,
            ['action' => $this->generateUrl('modification_film_suite',
                array('id' => $film->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            try {
                $entityManager->persist($film);
                $entityManager->flush();
                $url = $this->generateUrl('detail_film',
                    array('id' => $film->getId()));
                return $this->redirect($url);
            }catch (UniqueConstraintViolationException $e){
                $message = "Le titre que vous avez fourni existe déjà";

                return $this->render('cineland/modifierFilm.html.twig',
                    array('monFormulaire' => $form->createView(), 'errorMessage' => $message));
            }
        }
        return $this->render('cineland/modifierFilm.html.twig',
            array('monFormulaire' => $form->createView(),'errorMessage' => null));
    }

    //Action 13
    public function action13() {

        return $this->render('cineland/action13.html.twig');

    }

    //Action 13 Suite
    public function action13Suite(Request $request) {

        $date1 = $request->request->get("date1");
        $date2 = $request->request->get("date2");
        $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
        $films = $repo->action13Query($date1,$date2);

        return $this->render('cineland/action13Suite.html.twig', array('films' => $films,'date1'=>$date1,'date2'=>$date2));

    }

    //Action 14
    public function action14(Request $request){
        $date = $request->request->get("dateD");
        if ($date == null){
            return $this->render('cineland/action14.html.twig');
        } else {
            $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);

            $result = $repo->action14Query($date);
            $firstTitle = "Les films dont la date de sortie est antérieure à " . date("d-m-Y", strtotime($date));

            return $this->render('cineland/action14Suite.html.twig',
                array('films' => $result, 'firstTitle' => $firstTitle));
        }
    }

    //Action 17
    public function action17() {

        return $this->render('cineland/action17.html.twig');

    }

    //Action 17 Suite
    public function action17Suite(Request $request) {

        $nom1 = $request->request->get("nom1");
        $nom2 = $request->request->get("nom2");
        $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
        $films = $repo->action17Query($nom1,$nom2);

        return $this->render('cineland/action17Suite.html.twig', array('films' => $films));

    }

    //Action 23
    public function action23() {

        $films = $this->getDoctrine()
            ->getRepository(Film::class)->findAll();
        return $this->render('cineland/action23.html.twig',
            array('films' => $films));

    }

    //Action 23 suite
    public function modifierNoteFilm23(Request $request) {

        $id = $request->request->get("ID");
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);

        return $this->render('cineland/FilmAJAX/noteFilmAJAX.html.twig',
            array('film' => $film));

    }

    //Action 23 v2
    public function modifierNoteFilm23V2(Request $request) {

        $titre = $request->request->get("TITRE");
        $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
        $film = $repo->action23QueryV2($titre);
        //$film = $this->getDoctrine()->getRepository(Film::class)->find($titre);

        if(!$film) {
           // throw $this->createNotFoundException('Film[id=' . $titre . '] inexistante');
            return $this->render('cineland/rechercheIntrouvable.html.twig');
        }else {
            return $this->render('cineland/FilmAJAX/noteFilmAJAXV2.html.twig',
                array('film' => $film));
        }

    }

    //Action 23 suite
    public function modifierNoteFilmSuite23(Request $request) {

        $note = $request->request->get("note");
        $id = $request->request->get("id");
        $entityManager = $this->getDoctrine()->getManager();
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);

        if(!$film)
            throw $this->createNotFoundException('Film[id='.$id.'] inexistante');
        $film->setNote($note);
        $entityManager->persist($film);
        $entityManager->flush();

        $films = $this->getDoctrine()
            ->getRepository(Film::class)->findAll();
        return $this->render('cineland/action23.html.twig',
            array('films' => $films));

    }

    //Action 25
    public function action25() {

        return $this->render('cineland/action25.html.twig');

    }

    //Action 25 suite
    public function action25Suite(Request $request) {

        $titre = $request->request->get("titre");

        $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
        $films = $repo->action25Query($titre);

        return $this->render('cineland/action25Suite.html.twig', array('films' => $films));

    }

    //Des fonctions supplémentaires

    public function GetDetailFilm(Request $request)
    {

        $id = $request->request->get("ID");
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);

        if(!$film)
            throw $this->createNotFoundException('Film[id='.$id.'] inexistante');
        return $this->render('cineland/FilmAJAX/detailFilmAJAX.html.twig',
            array('film' => $film));

    }
    public function filmByActeur()
    {

        return $this->render('cineland/accueil/filmByActeur.html.twig');

    }

    public function filmByActeurSuite(Request $request)
    {

        $nom = $request->request->get("nom");
        $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
        $films = $repo->findFilmByActeur($nom);

        return $this->render('cineland/accueil/filmByActeurSuite.html.twig',
            array('films' => $films));

    }

    public function filmByGenre()
    {

        return $this->render('cineland/accueil/filmByGenre.html.twig');

    }

    public function filmByGenreSuite(Request $request)
    {

        $nom = $request->request->get("nom");
        $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
        $films = $repo->findFilmByGenre($nom);

        return $this->render('cineland/accueil/filmByGenreSuite.html.twig',
            array('films' => $films));

    }

    public function BarreRechercheFilms(Request $request)
    {
        $titre = $request->request->get("titre");

        $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
        $films = $repo->findFilmsByPartOfTitre($titre);
        return $this->render('cineland/accueil/barreRechercheFilms.html.twig',
            array('films' => $films));

    }

}

