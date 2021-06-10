<?php
namespace App\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Acteur;
use App\Entity\Film;
class ActeurType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nomPrenom', TextType::class,[
            'attr' => [
                'class' => 'form-control',
            ],
            'error_bubbling' => true,
        ])
            ->add('dateNaissance', DateType::class , array(
                'label' => 'Date de Naissance',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ]
            ))
            ->add('nationalite', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('film', EntityType::class,
                array('class' => Film::class,
                'choice_label' => 'titre',
                'expanded' => true,
                'multiple' => true));
    }
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Acteur::class,
        ));
    }
}