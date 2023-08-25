<?php

namespace App\Form;

use App\Entity\Advice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdviceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('compost')
            ->add('water_collector')
            ->add('rainfall_rate_need')
            ->add('sunshine_rate_need')
            ->add('garden_size')
            ->add('categories')
            ->add('usefulnesses')
            ->add('ground_acidities')
            ->add('ground_types')
            ->add('shadow_types')
            ->add('departments')
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advice::class,
        ]);
    }
}
