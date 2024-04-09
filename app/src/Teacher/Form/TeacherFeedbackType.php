<?php

namespace App\Teacher\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TeacherFeedbackType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'label' => 'teacher.feedback.name'
            ])
            ->add('message', TextareaType::class, [
                'label' => 'teacher.feedback.message',
                'attr' => [
                    'rows' => 8
                ]
            ]);
    }
}
