<?php

return [
    'admin' => [
        'accounts' => [
            'form' => [
                'header' => 'Dodaj nowe konto',
                'submit' => 'Zapisz konto',
            ],
            'admins' => [
                'header' => 'Administratorzy',
                'create' => 'Dodaj nowego administratora',
            ],
            'clients' => [
                'header' => 'Użytkownicy',
            ],
            'manage' => [
                'admin' => [
                    'disable_text' => '
                        <span class="text-start">
                        <div class="w-100 text-center">
                            Czy na pewno chcesz wyłączyć konto użytkownika
                            <br>
                            {name}?
                        </div>
                        <br>
                        &bull; Użytkownik nie będzie mógł się zalogować do systemu.
                        </span>
                    ',
                    'restore_text' => '
                        <span class="text-start">
                        <div class="w-100 text-center">
                            Czy na pewno chcesz przywrócić konto użytkownika
                            <br>
                            {name}?
                        </div>
                        </span>
                    '
                ],
                'client' => [
                    'disable_text' => '
                        <span class="text-start">
                        <div class="w-100 text-center">
                            Czy na pewno chcesz usunąć konto użytkownika
                            <br>
                            {name}?
                        </div>
                        <br>
                        <b>Ta akcja jest nieodwracalna!</b>

                        <br><br>
                        <ul>
                            <li>Użytkownik nie będzie mógł się zalogować do systemu.</li>
                            <li>Wszystkie zgłoszenia użytkownika zostaną usunięte.</li>
                        </ul>
                        </span>
                    ',
                ]
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
        'posting' => [
            'header' => 'Zarządzaj zajęciami',
        ]
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
            ',
            'submit' => 'Zaloguj się'
        ],
    ],
    'index' => [
        'header' => '&bull; Zapisz się na zajęcia',
        'signed_up_list' => 'Zajęcia na które jesteś zapisany'
    ],
    'user' => [
        'application' => [
            'title' => 'Zgłoszenie #{application_id} na zajęcia "{posting_title}"',
            'details' => [
                'header' => 'Szczegóły zgłoszenia',
                'posting' => 'Zajęcia',
                'date_applied' => 'Data zgłoszenia',
                'managed_by' => 'Zarządzane przez',
            ],
            'help' => 'W przypadku pytań lub zmiany danych prosimy o kontakt z
                <a href="mailto:{email}">{email}</a>',
        ],
        'posting_list' => [
            'filter' => [
                'assigned_to' => 'Prowadzący',
                'title' => 'Tytuł',
            ]
        ]
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
        ],
        'disabled_account' => 'Twoje konto zostało wyłączone. Skontaktuj się z administratorem serwisu w celu uzyskania pomocy.',
    ],
    'components' => [
        'posting' => [
            'sign_up' => 'Zapisz się',
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
                other {<b>#</b> Kandydatów}
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
            ],
            'form' => [
                'title' => 'Tytuł',
                'description' => 'Opis',
                'assigned_to' => 'Prowadzący',
                'questionnaire' => 'Zestaw pytań',
                'success' => 'Zajęcia zostały zapisane',
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
            'status' => 'Status',
            'account_status' => '{status, select,
                active {Aktywny}
                inactive {Nieaktywny}
                other {Nieznany}
            }',
        ],
        'question' => [
            'first_name' => 'Imię',
            'last_name' => 'Nazwisko',
            'email' => 'Adres email',
            'phone' => 'Numer telefonu',
            'age' => 'Wiek',
            'pesel' => 'PESEL',
            'city' => 'Miasto',
            'street' => 'Ulica',
            'house_no' => 'Numer domu',
            'postal_code' => 'Kod pocztowy',
        ]
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
        'restore' => 'Przywróć',
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
        'success' => 'Sukces',
        'are_you_sure' => 'Czy na pewno chcesz to zrobić?',
        'confirm_your_action' => 'Potwierdź swoją akcję',
        'number_of_items' => 'Liczba elementów',
        'items_per_page' => 'Elementów na stronie',
        'search' => 'Szukaj',
        'preview' => 'Podgląd',
    ],
    'emails' => [
        'security' => [
            'password_reset' => [
                'header' => '<h1>Odzyskiwanie hasła</h1>',
                'text' => '
                    Otrzymujesz tę wiadomość, ponieważ otrzymaliśmy prośbę o zresetowanie hasła do Twojego konta.
                    <br>
                    Twoje nowe hasło to: <b>{password}</b>
                    <br><br>
                    &bull; Po zalogowaniu się na swoje konto, zostaniesz poproszony o zmianę hasła.

                    <br><br>
                    Możesz zalogować się na swoje konto klikając w poniższy link: <br>
                    <a href="{link}">{link}</a>

                    <br><br>
                    &bull; Jeśli nie prosiłeś o zmianę hasła, skontaktuj się z administratorem serwisu.',
            ]
        ],
        'noreply' => '<b>Ta wiadomość została wysłana automatycznie. Prosimy na nią nie odpowiadać.</b>',
    ]
];
