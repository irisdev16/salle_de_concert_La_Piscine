<?php

namespace App\Form;

use App\Entity\Establishment;
use App\Entity\Room;
use App\Entity\Tag;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, options:[
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => "Nom de la salle"
            ])

            ->add('capacity', options:[
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => "Capacité"
            ])

            ->add('establishment', EntityType::class, [
                'class' => Establishment::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un établissement',
                'attr'=> ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Etablissement',
            ])

            ->add('images', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'label' => 'Images',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
            ])

            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'attr' => ['class' => 'form-select'],
                'label' => 'Tags',
                'label_attr' => ['class' => 'form-label'],
            ])

            ->add('Enregistrer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success mt-3']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
