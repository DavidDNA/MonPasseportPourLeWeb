<?php

namespace App\Classroom\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

class ClassroomWizardStudents extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('studentsAmount', NumberType::class, [
            'label' => false,
            'constraints' => [
                new Range(min: 3, max: 5, groups: ['tulas'])
            ]
        ]);
    }

    public function getBlockPrefix(): string {
        return 'createClassroomStep3';
    }
}
