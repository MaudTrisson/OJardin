<?php

namespace App\Form;

use App\Entity\Plant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'image',
                'required' => false,
                //du côté BDD l'image est du texte, on veut pas que symfony s'occupe de l'enregistrement, on le fera nous même
                'mapped' => false,

                //vos contraintes (k=1000)
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'L\'image n\'est pas valide.',
                    ])
                ],
            ])
            ->add('lifetime')
            ->add('recommending_planting_date')
            ->add('flowering_start')
            ->add('flowering_end')
            ->add('leaves_persistence')
            ->add('height')
            ->add('width')
            ->add('rainfall_rate_need')
            ->add('sunshine_rate_need')
            ->add('freeze_sensibility_max')
            ->add('color')
            ->add('categories')
            ->add('usefulnesses')
            ->add('ground_acidities')
            ->add('ground_types')
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plant::class,
        ]);
    }
}
