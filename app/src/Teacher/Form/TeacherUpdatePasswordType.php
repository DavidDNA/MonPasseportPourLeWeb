<?php

namespace App\Teacher\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TeacherUpdatePasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'teacher.update.password.old'
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'teacher.update.password.new'],
                'second_options' => ['label' => 'teacher.update.password.repeat'],
                'constraints' => [
                    new NotBlank(),
                    new Regex("/^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).*$/", "teacher.update.password.invalid")
                ]
            ]);
    }
}
