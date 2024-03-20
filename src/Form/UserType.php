<?php

namespace App\Form;

use App\Entity\User;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\IsTrue;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('pwd',PasswordType::class)
            ->add('date_N')
            ->add('num_tel')
            ->add('adress')
            ->add('photo', FileType::class, [
                'label' => 'Image',
                'required' => false, // Si l'image n'est pas obligatoire
                'mapped' => false, // Ne pas mapper directement le champ à l'entité
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'role',
                'choices' => [
                    'Client' => 'client',
                    'Coach' => 'coach',
                ],
                'expanded' => true, // Set to true for radio buttons, false for a dropdown
                'multiple' => false, // Set to true for multiple choices
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3 ([
                    'message' => 'karser_recaptcha3.message',
                    'messageMissingValue' => 'karser_recaptcha3.message_missing_value',
                ]),
            ]);
        $builder->get('photo')->addModelTransformer(new FileToViewTransformerr());

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

class FileToViewTransformerr implements DataTransformerInterface
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
