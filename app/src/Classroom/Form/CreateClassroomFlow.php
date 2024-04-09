<?php

namespace App\Classroom\Form;

use Craue\FormFlowBundle\Form\FormFlow;

class CreateClassroomFlow extends FormFlow {

    protected function loadStepsConfig(): array {
        return [
            [
                'label' => 'year group',
                'form_type' => ClassroomWizardYearGroup::class
            ],
            [
                'label' => 'sessions',
                'form_type' => ClassroomWizardSessionsPlan::class
            ],
            [
                'label' => 'students',
                'form_type' => ClassroomWizardStudents::class,
                'form_options' => [
                    'validation_groups' => ['Default']
                ]
            ],
        ];
    }
}
