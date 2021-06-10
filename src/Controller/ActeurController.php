<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Form\Type\FilmType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
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
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genre;
use App\Entity\Film;

class ActeurController extends AbstractController
{
    //Action 3
    public function listeActeur()
    {
        $acteurs = $this->getDoctrine()
            ->getRepository(Acteur::class)->findAll();
        return $this->render('cineland/listeActeur.html.twig',
            array('acteurs' => $acteurs));

    }

    //Action 5
    public function ajouterActeur(Request $request)
    {
        $acteur = new Acteur;
        $film = new Film;
        $form = $this->createForm(ActeurType::class, $acteur);
        $form->add('submit', SubmitType::class,
            array('label' => 'Ajouter','attr'=>['class' => 'btn btn-theme']));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            try {
                $entityManager->persist($acteur);
                $entityManager->flush();
                return $this->redirectToRoute('liste_acteur');
            }catch (UniqueConstraintViolationException $e) {
                $message = "Le nom que vous avez fourni existe déjà";

                return $this->render('cineland/ajouterActeur.html.twig',
                    array('monFormulaire' => $form->createView(), 'errorMessage' => $message));
            }
        }
        return $this->render('cineland/ajouterActeur.html.twig',
            array('monFormulaire' => $form->createView(),'errorMessage' => null));
    }

    //Action 4
    public function listeActeurForDetails(Request $request) {

        $acteurs = $this->getDoctrine()->getRepository(Acteur::class)->findAll();
        return $this->render('cineland/listeActeurForDetails.html.twig', array('acteurs' => $acteurs));
    }

    //Action 4 Suite
    public function detailActeur(Request $request){
        $id = $request->request->get("id");

        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if(!$acteur)
            throw $this->createNotFoundException('Acteur[id='.$id.'] inexistante');
        return $this->render('cineland/detailActeur.html.twig', array('acteur' => $acteur));
    }

    //Action 4 Suite v2
    public function detailActeurV2($id){
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if(!$acteur)
            throw $this->createNotFoundException('Acteur[id='.$id.'] inexistante');
        return $this->render('cineland/detailActeur.html.twig', array('acteur' => $acteur));
    }

    //Action 6
    public function listeActeurForModification()
    {
        $acteurs = $this->getDoctrine()
            ->getRepository(Acteur::class)->findAll();
        return $this->render('cineland/action6GetActeur.html.twig',
            array('acteurs' => $acteurs));

    }

    //Action 6
    public function modifierActeur($id) {

        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if(!$acteur)
            throw $this->createNotFoundException('Acteur[id='.$id.'] inexistante');
        $form = $this->createForm(ActeurType::class, $acteur,
            ['action' => $this->generateUrl('modification_acteur_suite',
                array('id' => $acteur->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render('cineland/modifierActeur.html.twig',
            array('monFormulaire' => $form->createView(), 'errorMessage' => null));
    }

    //Action 6 v2
    public function modifierActeurV2(Request $request) {
        $id = $request->request->get("id");

        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if(!$acteur)
            throw $this->createNotFoundException('Acteur[id='.$id.'] inexistante');
        $form = $this->createForm(ActeurType::class, $acteur,
            ['action' => $this->generateUrl('modification_acteur_suite',
                array('id' => $acteur->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        return $this->render('cineland/modifierActeur.html.twig',
            array('monFormulaire' => $form->createView(), 'errorMessage' => null));
    }

    //Action 6 Suite
    public function modifierActeurSuite(Request $request, $id) {

        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if(!$acteur)
            throw $this->createNotFoundException('Acteur[id='.$id.'] inexistante');
        $form = $this->createForm(ActeurType::class, $acteur,
            ['action' => $this->generateUrl('modification_film_suite',
                array('id' => $acteur->getId()))]);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            try {
                $entityManager->persist($acteur);
                $entityManager->flush();
                $url = $this->generateUrl('acteur_DetailsV2',
                    array('id' => $acteur->getId()));
                return $this->redirect($url);
            }catch (UniqueConstraintViolationException $e){
                $message = "Le nom que vous avez fourni existe déjà";

                return $this->render('cineland/modifierActeur.html.twig',
                    array('monFormulaire' => $form->createView(), 'errorMessage' => $message));
            }
        }
        return $this->render('cineland/modifierActeur.html.twig',
            array('monFormulaire' => $form->createView(), 'errorMessage' => null));
    }


    //Action 7
    public function supprimerActeur(Request $request)
    {
        $id = $request->request->get("ID");
        $em = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository(Acteur::class);
        $acteur = $repo->find($id);
        $em->remove($acteur);
        $em->flush();

        return $this->redirectToRoute('liste_acteur');
    }



    //Action 7
    public function listeActeurForSuppression()
    {
        $acteurs = $this->getDoctrine()
            ->getRepository(Acteur::class)->findAll();
        return $this->render('cineland/listeActeurForSuppressionActeur.html.twig',
            array('acteurs' => $acteurs));

    }

    //Action 15
    public function action15()
    {

        return $this->render('cineland/action15.html.twig');

    }

    //Action 15 suite
    public function action15Suite(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
        $titre = $request->request->get("titre");
        $acteurs = $repo->action15Query($titre);

        return $this->render('cineland/action15Suite.html.twig', array('acteurs' => $acteurs));


    }

    //Action 16
    public function action16(Request $request){

        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);

        $result = $repo->action16Query();
        $firstTitle = "Les acteurs ayant joué dans au moins 3 films différents";

        return $this->render('cineland/action16.html.twig',
            array('acteurs' => $result, 'firstTitle' => $firstTitle));
    }

    //Action 18
    public function action18(Request $request){

        $id = $request->request->get("id");
        if ($id == null){
            $acteurs = $this->getDoctrine()
                ->getRepository(Acteur::class)->findAll();
            return $this->render('cineland/action18.html.twig',
                array('acteurs' => $acteurs));
        } else {
            $acteur = $this->getDoctrine()
                ->getRepository(Acteur::class)->find($id);

            $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);

            $result = $repo->action18Query($id);

            return $this->render('cineland/action18Suite.html.twig',
                array('genres' => $result, 'nom' => $acteur->getNomPrenom()));
        }
    }

    //Action 19
    public function action19() {

        return $this->render('cineland/action19.html.twig');

    }

    //Action 19 suite
    public function action19Suite(Request $request) {

        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);

        $nom = $request->request->get("nom");
        $acteur = $repo->findNomActeur($nom);
        if(!$acteur)
            throw $this->createNotFoundException('Acteur '.$nom.' inexistante');

        $acteur = $repo->action19Query($nom);
       // print_r($acteur);

        return $this->render('cineland/action19Suite.html.twig', array('acteur' => $acteur));

    }

    //Action 20
    public function action20(Request $request){

        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);

        $result = $repo->action20Query();

        return $this->render('cineland/action20.html.twig',
            array('acteurs' => $result));
    }

    //Action 21
    public function action21() {

        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
        $acteurs = $this->getDoctrine()
            ->getRepository(Acteur::class)->findAll();
        $acteursResultat = array();
        foreach ($acteurs as &$acteur) {
            $obj = new \stdClass();
            $result = $repo->action21Query($acteur->getNomPrenom());
            if (empty($result)) {
                continue;
            }
            $obj->acteur = $acteur;
            $obj->nomGenre = $result;
            array_push($acteursResultat, $obj);

        }
                return $this->render('cineland/action21.html.twig', array('acteurs' => $acteursResultat));

    }

    //Action 26
    public function action26(Request $request){

        $id = $request->query->get("id");
        $ageM = $request->query->get("value26");

        if ($id == null){
            $acteurs = $this->getDoctrine()
                ->getRepository(Acteur::class)->findAll();
            return $this->render('cineland/action26.html.twig',
                array('acteurs' => $acteurs));
        } else {
            $entityManager = $this->getDoctrine()->getManager();

            $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);

            $result = $repo->action26Query($id);

            $film = null;
            foreach ($result as $r){
                $film = $this->getDoctrine()
                    ->getRepository(Film::class)->find($r);
                $film->setAgeMinimal($film->getAgeMinimal() + ($ageM ? $ageM : 1));
                $entityManager->flush();
            }

            return $this->redirectToRoute('liste_film');
        }
    }

    //Des fonctions supplémentaires

    public function GetDetailActeurFilm(Request $request)
    {


        $id = $request->request->get("ID");
        $film = $this->getDoctrine()->getRepository(Film::class)->find($id);

        if(!$film)
            throw $this->createNotFoundException('Film[id='.$id.'] inexistante');
        return $this->render('cineland/ActeurAJAX/detailActeurAJAX.html.twig',
            array('film' => $film));

    }
    public function BarreRechercheActeurs(Request $request)
    {
        $nom = $request->request->get("nom");

        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
        $acteurs = $repo->findActeursByPartOfNom($nom);
        return $this->render('cineland/accueil/barreRechercheActeurs.html.twig',
            array('acteurs' => $acteurs));

    }

    public function getActeurDetails(Request $request){
        $id = $request->query->get("ID");
        $acteur = $this->getDoctrine()->getRepository(Acteur::class)->find($id);
        if(!$acteur)
            throw $this->createNotFoundException('Acteur[id='.$id.'] inexistante');
        return $this->render('cineland/acteurAjax/detailActeurAJAX.html.twig', array('acteur' => $acteur));
    }

}


