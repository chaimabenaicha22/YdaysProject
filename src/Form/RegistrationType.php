<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\ApplicationType;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,$this->getConfiguration("Prenom","Votre prenom"))
            ->add('lastName',TextType::class,$this->getConfiguration("Nom","Votre nom de famille"))
            ->add('email',EmailType::class,$this->getConfiguration("Email","Votre adresse email.."))
            ->add('picture',UrlType::class,$this->getConfiguration("Photo de Profil","URL de votre avatar..."))
            ->add('hash',PasswordType::class,$this->getConfiguration("Mot de passe","Choisissez un MDP"))
            ->add('passwordConfirm',PasswordType::class,$this->getConfiguration("Confirmation de mot de passe","Veuillez confirmer cotre mot de passe"))
            ->add('introduction',TextType::class,$this->getConfiguration("Introduction","Presentez vous"))
            ->add('description',TextareaType::class,$this->getConfiguration("description detaillée","presentez more details!!!"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}