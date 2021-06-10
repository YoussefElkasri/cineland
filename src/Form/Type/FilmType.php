<?php
namespace App\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;

class FilmType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('titre', TextType::class, array(
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                ]))
            ->add('duree', IntegerType::class, array(
                'label' => 'Durée',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ]))
            ->add('note', IntegerType::class, array(
                'label' => 'Note',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                    'max' => 20,
                ]))
            ->add('ageMinimal', IntegerType::class, array(
                'label' => 'Age minimal',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ]))
            ->add('dateSortie', DateType::class, array(
                'label' => 'Date de sortie',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ]
            ))
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un valeur',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            //->add('acteurs')
            ->add('acteurs' , EntityType::class , array(
                'class' => Acteur::class,
                'choice_label' => 'nomPrenom',
                'expanded' => true,
                'multiple' => true,
                'by_reference' => false,
            ));
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Film::class,
        ));
    }
}