<?php

return [
    'components' => [
        'posting' => [
            'question' => [
                'age' => [
                    'notInRangeMessage' => 'Wiek musi być pomiędzy {{ min }} a {{ max }}',
                    'minMessage' => 'Wiek musi wynosić {{ limit }} lub więcej',
                    'maxMessage' => 'Wiek musi wynosić {{ limit }} lub mniej',
                ],
                'bonus_criteria_consent' => 'Przy wybraniu kryteriów dodatkowych wymagana jest zgoda na okazanie dodatkowych dokumentów',
            ],
            'cannot_disable_completed' => 'Nie można usunąć zakończonej rekrutacji',
        ],
    ],
];
