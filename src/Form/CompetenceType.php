<?php

namespace App\Form;

use App\Entity\Competence;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('img', FileType::class,[
                
                'mapped'=> false,
                'help' => 'png, jpg ,jpeg, jp2, webp',
                'constraints' => [
                    new Image([
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'Le fichier est trop voluminineux({{ size }} {{suffix}}). Maximum autorisé: {{limit}} {{suffix}}.',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/jp2',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Merci de selectionner une image au format {{types}}'
                    ])
                ]
            ])
            ->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competence::class,
        ]);
    }
}
