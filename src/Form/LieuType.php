<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom  '])
            ->add('street', null, ['label' => 'Rue  '])
            ->add('ville', EntityType::class, [
                'class'=>Ville::class,
                'label'=>'Ville',
                'choice_label'=>'name'
            ])
            ->add('latitude', null, [
                'label' => 'Latitude  ',
                'attr' => ['readonly'=> true]
            ])
            ->add('longitude', null, [
                'label' => 'Longitude  ',
                'attr' => ['readonly'=> true]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
