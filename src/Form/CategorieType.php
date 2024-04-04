<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Builder permettant de définir les champs du formulaire d'ajout ou d'édition
 * d'une categorie
 */
class CategorieType extends AbstractType {
   
    /**
     * Ajout des champs pour le formulaire "formcategorie"
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('name', TextType::class, [
                    'label' => 'Categorie',
                    'required' => true])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
    }
}