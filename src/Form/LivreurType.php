<?php

namespace App\Form;

use App\Entity\Livreur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
<<<<<<< HEAD
use Sbyaute\StarRatingBundle\Form\StarRatingType;
=======

>>>>>>> 8188c65b1cba03a243414e6a61dc83f925ac8bbe
class LivreurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('disponibilite')
            ->add('image', FileType::class, [
                'label' => 'image',
                'required' => false, // Si l'image n'est pas obligatoire
                'mapped' => false, // Ne pas mapper directement le champ à l'entité
            ])
<<<<<<< HEAD
            ->add('note', StarRatingType::class, [
                'label' => 'note'
            ]);
=======
>>>>>>> 8188c65b1cba03a243414e6a61dc83f925ac8bbe
        ;
        $builder->get('image')->addModelTransformer(new FileToViewTransformerss());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livreur::class,
        ]);
    }
}

class FileToViewTransformerss implements DataTransformerInterface
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
