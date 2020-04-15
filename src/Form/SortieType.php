<?php

namespace App\Form;

use App\Entity\Sortie;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,['label'=> 'Nom de la sortie : '])
            ->add('dateTimeStart',null,[
                'widget' => 'choice'])
            ->add('duration',null,['label'=> 'Durée de la sortie :'])
            ->add('deadlineRegistration',null,[
                'label'=> 'Date Limite Inscription'
            ])
            ->add('maxNumberRegistration',null,
                ['label'=> 'Nombre maximum de participants : '
            ])
            ->add('site', null, ['label' => 'Site de rattachement :', 'choice_label' => 'name'])
            ->add('description',null,['label'=>'Description de la sortie : '])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
