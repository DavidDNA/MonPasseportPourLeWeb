<?php

namespace App\Session\Form;

use App\Entity\SessionActivity;
use App\Form\QuillJsEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionActivityType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title', TextType::class, [
                'label' => 'session.detail.activity.title'
            ])
            ->add('duration', TextType::class, [
                'label' => 'session.detail.activity.duration',
                'required' => false
            ])
            ->add('content', QuillJsEditorType::class, [
                'required' => false,
                'label' => 'session.detail.activity.content',
                'theme' => 'bubble'
            ])
            ->add('resources', QuillJsEditorType::class, [
                'required' => false,
                'label' => 'session.detail.activity.resources',
                'theme' => 'bubble'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => SessionActivity::class,
        ]);
    }
}
