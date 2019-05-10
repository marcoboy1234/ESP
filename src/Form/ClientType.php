<?php

namespace App\Form;

use App\Entity\AdresseClient;
use App\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

Class ClientType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('Email', EmailType::class, [
                'invalid_message' => 'Email invalide'
            ])
            ->add('Username')
            ->add('Password', RepeatedType::class, [
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirmation du password'],
                'type' => PasswordType::class,
                'invalid_message' => 'Le password doit être identique',
                'options' => ['attr' => ['class' => 'password-field'],]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}