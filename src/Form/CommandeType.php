<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Livreur; // Assurez-vous que le chemin est correct
use App\Entity\Panier;
use App\Entity\User;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statut')
            ->add('livreur', EntityType::class, [
                'class' => Livreur::class,
                'choice_label' => 'nom', // Remplacez 'nom' par le champ approprié à afficher
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id', // Remplacez 'username' par le champ approprié à afficher
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
