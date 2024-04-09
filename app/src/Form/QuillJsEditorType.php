<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuillJsEditorType extends TextareaType {

    public function buildView(FormView $view, FormInterface $form, array $options) {
        $view->vars['attr']['data-quilljs'] = $options['theme'];
        parent::buildView($view, $form, $options);
    }

    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'required' => false,
            'theme' => 'snow'
        ]);
    }
}
