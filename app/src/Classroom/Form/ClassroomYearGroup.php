<?php

namespace App\Classroom\Form;

use App\Entity\Classroom;
use App\Entity\YearGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassroomYearGroup extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('yearGroup', EntityType::class, [
                'expanded' => true,
                'class' => YearGroup::class,
                'choice_label' => function (YearGroup $yearGroup) {
                    return "classroom.create." . $yearGroup->getName();
                },
                'choice_attr' => function (YearGroup $yearGroup) {
                    return ['disabled' => !$yearGroup->isEnabled()];
                },
                'choice_translation_domain' => true,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Classroom::class,
        ]);
    }
}
