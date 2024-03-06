<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Exercice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class ExerciceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('des')
            ->add('mc')
            ->add('nd')
            ->add('img', FileType::class, [
                'label' => 'Image',
                'required' => false, // Si l'image n'est pas obligatoire
                'mapped' => false, // Ne pas mapper directement le champ à l'entité
            ])
            ->add('gif', FileType::class, [
                'label' => 'GIF',
                'required' => false, // Si le GIF n'est pas obligatoire
                'mapped' => false, // Ne pas mapper directement le champ à l'entité
            ])
            ->add('Sauvegarder', SubmitType::class);
        
        // Ajouter le transformateur de vue pour le champ d'image
        $builder->get('img')->addModelTransformer(new FileToViewTransformer());
        $builder->get('gif')->addModelTransformer(new FileToViewTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurez ici les options par défaut pour votre formulaire
        ]);
    }
}

class FileToViewTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // Lors de l'affichage du formulaire, retournez null pour éviter les erreurs
        return null;
    }

    public function reverseTransform($value)
    {
        // Transformez le chemin de fichier en instance de Symfony\Component\HttpFoundation\File\File
        // Si $value est null, retournez null
        if (!$value) {
            return null;
        }

        return new File($value);
    }
}