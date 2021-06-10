<?php

namespace App\Controller;

use App\Entity\Acteur;
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

class CinelandController extends AbstractController
{
    public function accueil()
    {
        return $this->render('cineland/accueil.html.twig');
    }


}

