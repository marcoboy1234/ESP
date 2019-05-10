<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Rabais;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RabaisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Date_De_Debut')
            ->add('Date_De_Fin')
            ->add('Rabais', null, [
                'label' => 'Veuiller mettre un pourcentage pour le rabais'
            ])
            ->add('Employe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rabais::class,
        ]);
    }
}
