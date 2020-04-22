<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,['label'=> 'Nom de la sortie  '])
            ->add('dateTimeStart',null,['label'=> 'Début de la sortie ',
                'widget' => 'choice'])
            ->add('duration',null,['label'=> 'Durée de la sortie '])
            ->add('deadlineRegistration',null,[
                'label'=> 'Date Limite Inscription '
            ])
            ->add('maxNumberRegistration',null,
                ['label'=> 'Nombre maximum de participants '
            ])
            ->add('site', null, ['label' => 'Site de rattachement ', 'choice_label' => 'name'])
            ->add('description',null,['label'=>'Description de la sortie  '])
            ->add('lieu',EntityType::class,[
                'class'=>Lieu::class,
                'label'=> 'Lieu de la sortie',
                'choice_label'=> 'name',
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
