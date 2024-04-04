<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use DateTime;

/**
 * Definition des champs d'ajout/edition d'une formation
 */
class FormationType extends AbstractType {
    
    /**
     * Champs pour le formulaire "formformation"
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Formation',
                'required' => true
            ])
            ->add('publishedAt', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date',
            'data' => isset($options['data']) && $options['data']->getPublishedAt() != null ? $options['data']->getPublishedAt(): new DateTime('now'),
            'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'Catégorie',
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ])
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'label' => 'Playlist',
                'choice_label' => 'name',
                'multiple' => false,
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);     
    }
}