<?php

return [
    'admin' => [
        'accounts' => [
            'form' => [
                'header' => 'Dodaj nowe konto',
                'submit' => 'Zapisz konto',
            ]
        ],
        'dashboard' => [
            'header' => 'Panel administratora',
            'items' => [
                'create_admin' => 'Create new Admin user here',
                'admin' => 'Manage all Admin users here',
                'client' => 'Manage all Client users here',
                'posting' => [
                    'super_admin' => 'Manage all Postings here',
                    'admin' => 'Manage your Postings here',
                ],
                'questionaire' => 'Manage questionaire'
            ]
        ],
    ],
    'privacy_policy' => [
        'header' => '&bull; Polityka prywatności',
        'failed_auto_login' => 'Nie udało się zalogować na nowo utworzone konto.
            <br/>
            <br/>

            &bull; Na podany adres email zostało wysłane nowe hasło.
            Proszę zalogować się na swoje konto przy użyciu nowego hasła i ponownie spróbować aplikować na ogłoszenie.',
        'email_taken' => 'Podany adres email jest już zajęty.
            <br/>
            <br/>
            &bull; Spróbuj zalogować się na swoje konto lub skorzystać z opcji "Zapomniałem hasła".',
        'apply_without_account' => [
            'header' => 'Aplikuj bez konta',
            'text' => '
                Wypełnij formularz aplikacyjny bez zakładania konta.<br/>
                Stworzymy dla Ciebie konto automatycznie korzystając z danych z formularza.
            ',
            'submit' => 'Aplikuj bez konta'
        ],
        'apply_with_account' => [
            'header' => 'Masz już konto?',
            'text' => '
                Zaloguj się, aby kontynuować aplikację i korzystać z innych funkcji.

                <br/>
                <br/>
                &bull; M.in. zapisywać swoje aplikacje, edytować swoje dane, przeglądać historię aplikacji.

                <br/>
                &bull; Oraz możliwości auto-uzupełniania na podstawie danych z poprzednich lat.
            ',
            'submit' => 'Zaloguj się'
        ],
    ],
    'index' => [
        'header' => '&bull; Zapisz się na zajęcia',
        'signed_up_list' => 'Zajęcia na które jesteś zapisany'
    ],
    'security' => [
        'logout' => 'Wyloguj się',
        'login' => 'Zaloguj się',
        'register' => 'Zarejestruj się',
        'form' => [
            'login' => [
                'header' => 'Zaloguj się',
                'submit' => 'Zaloguj się',
                'no_account' => 'Nie masz jeszcze konta?',
            ],
            'register' => [
                'header' => 'Zarejestruj się',
                'submit' => 'Zarejestruj się',
            ],
            'error' => [
                'already_logged_in' => 'Jesteś już zalogowany jako {name}.',
            ],
            'reset_password' => [
                'header' => 'Zresetuj hasło',
                'submit' => 'Zresetuj hasło',
            ],
            'email' => 'Adres email',
            'password' => 'Hasło',
            'password_confirmation' => 'Powtórz hasło',
        ]
    ],
    'components' => [
        'posting' => [
            'sign_up' => '{again, select,
                true {Zapisz się ponownie}
                other {Zapisz się}
             }',
            'sign_up_again' => 'Zapisz się ponownie',
            'assigned_to' => 'Prowadzi: {name}',
            'create' => [
                'header' => 'Utwórz nowe zajęcia',
                'create_new' => 'Utwórz nowe zajęcia',
                'no_postings_created' => 'Nie utworzono jeszcze żadnych zajęć. Stwórz nowe, żeby je tutaj zobaczyć.',
                'question' => [
                    'submit' => 'Dodaj pytanie',
                ]
            ],
            'candidates_count' => '{count, plural,
                =0 {Brak kandydatów}
                =1 {1 Zapis}
                =2 {2 Zapisy}
                =3 {3 Zapisy}
                =4 {4 Zapisy}
                other {# Zapisów}
            }',
            'candidate' => [
                'admin' => [
                    'list_header' => 'Kandydaci:',
                    'list_empty' => 'Jeszcze nie ma żadnych kandydatów. Jak tylko ktoś się zgłosi, zobaczysz ich tutaj.',
                ]
            ],
            'question' => [
                'admin' => [
                    'list_header' => 'Pytania do zajęć:',
                    'list_empty' => 'Brak jeszcze pytań specjalnych. Dodaj pytanie, aby zobaczyć je tutaj.',
                ]
            ]
        ],
        'user' => [
            'email' => 'Adres email',
            'name' => 'Imię i nazwisko',
            'role' => '{multiple, select,
                true {Role}
                other {Rola}
            }',
            'user_role' => '{role, select,
                ROLE_ADMIN {Administrator}
                ROLE_CLIENT {Użytkownik}
                ROLE_SUPER_ADMIN {Super Administrator}
                other {Nieznana Rola}
            }',
        ],
    ],
    'errors' => [
        'generic' => 'Wystąpił błąd podczas przetwarzania akcji. Proszę spróbować ponownie.
        <br/>
        <br/>
        &bull; Jeśli problem będzie się powtarzał, proszę skontaktować się z administratorem serwisu.'
    ],
    'common' => [
        'edit' => 'Edytuj',
        'back' => 'Wróć',
        'delete' => 'Usuń',
        'save' => 'Zapisz',
        'cancel' => 'Anuluj',
        'confirm' => 'Potwierdź',
        'yes' => 'Tak',
        'no' => 'Nie',
        'close' => 'Zamknij',
        'loading' => 'Ładowanie...',
        'actions' => 'Akcje',
        'undo' => 'Cofnij',
        'row_number' => 'Lp.',
    ]
];
