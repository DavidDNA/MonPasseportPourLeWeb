<?php

namespace App\Session\Form;

use App\Entity\Session;
use App\Entity\YearGroup;
use App\Form\QuillJsEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'label' => 'session.detail.name'
            ])
            ->add('title', TextType::class, [
                'label' => 'session.detail.title'
            ])
            ->add('PER', TextType::class, [
                'required' => false,
                'label' => 'session.detail.PER'
            ])
            ->add('PERMainColor', ColorType::class, [
                'label' => 'session.detail.PER.color.main'
            ])
            ->add('PERTextColor', ColorType::class, [
                'label' => 'session.detail.PER.color.text'
            ])
            ->add('PERObjectives', QuillJsEditorType::class, [
                'label' => 'session.detail.PER.objectives',
                'theme' => 'bubble'
            ])
            ->add('yearGroup', EntityType::class, [
                'class' => YearGroup::class,
                'choice_label' => function (YearGroup $yearGroup) {
                    return $yearGroup->getName();
                },
                'choice_translation_domain' => 'yeargroup',
                'label' => 'session.detail.year_group'
            ])
            ->add('objectives', QuillJsEditorType::class, [
                'label' => 'session.detail.objectives',
                'theme' => 'bubble'
            ])
            ->add('material', QuillJsEditorType::class, [
                'label' => 'session.detail.material',
                'theme' => 'bubble'
            ])
            ->add('materialUrl', TextType::class, [
                'label' => 'session.detail.materialUrl',
                'required' => false
            ])
            ->add('teacherIndications', QuillJsEditorType::class, [
                'label' => 'session.detail.teacher_indications',
                'theme' => 'bubble'
            ])
            ->add('activities', CollectionType::class, [
                'entry_type' => SessionActivityType::class,
                'label' => 'session.detail.activities',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('studentResources', QuillJsEditorType::class, [
                'label' => 'session.detail.student_resources',
                'theme' => 'bubble'
            ])
            ->add('summary', QuillJsEditorType::class, [
                'label' => 'session.detail.summary',
                'theme' => 'bubble'
            ]);

        $builder->get('PER')
            ->addModelTransformer(new CallbackTransformer(
                function ($PERArray) {
                    return is_array($PERArray) ? implode(',', $PERArray) : "";
                },
                function ($PERString) {
                    return explode(',', $PERString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Session::class
        ]);
    }
}
