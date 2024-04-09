<?php

namespace App\Classroom\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ClassroomWizardYearGroup extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('classroom', ClassroomYearGroup::class, [
            'label' => false
        ]);
    }

    public function getBlockPrefix(): string {
        return 'createClassroomStep1';
    }
}
