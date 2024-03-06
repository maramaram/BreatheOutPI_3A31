<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModifyUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom', null, [
                'label' => 'Nom ',
                'empty_data' => ''
            ])
            ->add('prenom', null,  [
                'label' => 'Prenom ',
                'empty_data' => ''
            ])
            ->add('email', null, [
                'label' => 'Email',
                'empty_data' => ''
            ])
            ->add('adress', null,  [
                'label' => 'Adress',
                'empty_data' => ''
            ])
            ->add('photo', FileType::class, [
                'label' => 'Image',
                'required' => false, // Si l'image n'est pas obligatoire
                'mapped' => false, // Ne pas mapper directement le champ à l'entité
            ])
            ->add('num_tel', null, [
                'label' => 'Phone number',
                'empty_data' => ''
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
