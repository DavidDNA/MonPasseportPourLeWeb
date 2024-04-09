<?php

namespace App\Classroom\Form;

use App\Entity\ClassroomWizard;
use App\Entity\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ClassroomWizardSessionsPlan extends AbstractType {

    public function __construct(private readonly TranslatorInterface $translator) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        /** @var ClassroomWizard $wizard */
        $wizard = $builder->getData();

        $builder
            ->add('sessions', EntityType::class, [
                'class' => Session::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => 'title',
                'choice_attr' => function (Session $session) use ($wizard) {
                    return [
                        'data-year-group' => $session->getYearGroup()->getId(),
                        'data-group-name' => $this->translator->trans(id: $session->getYearGroup()->getName(), domain: 'yeargroup'),
                        'checked' => $wizard->getClassroom()->getYearGroup()->getId() === $session->getYearGroup()->getId()
                    ];
                },
                'label' => false
            ])
            ->add('blockResourcesAccess', CheckboxType::class, [
                'label' => 'classroom.create.step.2.block_resources',
                'required' => false
            ]);
    }

    public function getBlockPrefix(): string {
        return 'createClassroomStep2';
    }
}
