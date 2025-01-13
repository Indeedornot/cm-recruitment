<?php

return [
    'components' => [
        'posting' => [
            'question' => [
                'age' => [
                    'notInRangeMessage' => 'Wiek musi być pomiędzy {{ min }} a {{ max }}',
                    'minMessage' => 'Wiek musi wynosić {{ limit }} lub więcej',
                    'maxMessage' => 'Wiek musi wynosić {{ limit }} lub mniej',
                    'age_min_max_invalid' => 'Wiek minimalny nie może być większy niż wiek maksymalny',
                ],
                'bonus_criteria_consent' => 'Przy wybraniu kryteriów dodatkowych wymagana jest zgoda na okazanie dodatkowych dokumentów',
            ],
            'cannot_disable_completed' => 'Nie można usunąć zakończonej rekrutacji',
            'schedule' => [
                'empty' => 'Musisz dodać co najmniej jeden termin zajęć',
            ]
        ],
    ],
    'validators' => [
        'phone_number' => [
            'mobile' => [
                'invalid' => 'Ten numer telefonu jest nieprawidłowy',
            ]
        ],
    ]
];
