<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom')
            ->add('Description')
            ->add('Prix')
            ->add('Categorie', EntityType::class,[
                'class' => Categorie::class,
                'choice_label' => 'Nom',
                'multiple' => false
            ])
            ->add('Photo', FileType::class, array(
                'label' => 'Choissisez votre photo',
                'data_class' => null,
                'attr' => array(
                    'type' => 'file',
                    'class' => 'custom-file-input'
                ),
                'required' => null
            ))
            ->add('Inventaire');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }

}
