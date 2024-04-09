<?php

namespace App\Classroom\Form;

use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassroomStudentState extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Student::class
        ]);
    }
}
