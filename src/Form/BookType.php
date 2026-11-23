<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du livre',
                'attr' => ['placeholder' => 'titre'],
                'required'=> true
            ])
            ->add('resume', TextareaType::class, [
                'label' => 'résumé du livre',
                'attr' => ['placeholder' => 'resumé'],
                'required'=> true

            ])
            ->add('publishAt', DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required'=> true
            ])
            ->add('author',TextType::class, [
                'label' => 'Auteur du livre',
                'attr' => ['placeholder' => 'auteur'],
                'required'=> true
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégories',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('save', SubmitType::class, [
                'label'=> 'Ajouter',
                'attr' => ['class' => 'btn mt-2'],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
