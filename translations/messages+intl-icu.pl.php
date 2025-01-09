<?php

return [
    'global' => [
        'brand' => 'CM Jordan'
    ],
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
                ],
                'disable' => [
                    'success' => 'Konto zostało wyłączone.',
                    'already_disabled' => 'To konto jest już wyłączone.',
                    'has_postings' => 'Nie można wyłączyć konta, które ma przypisane zajęcia.',
                    'cannot_disable_self' => 'Nie można wyłączyć własnego konta.',
                ]
            ]
        ],
        'dashboard' => [
            'header' => 'Panel administratora',
            'items' => [
                'create_admin' => 'Stwórz nowego administratora',
                'admin' => 'Zarządzaj wszystkimi administratorami',
                'client' => 'Zarządzaj wszystkimi użytkownikami',
                'posting' => [
                    'super_admin' => 'Zarządzaj wszystkimi zajęciami',
                    'admin' => 'Zarządzaj swoimi zajęciami',
                ],
                'global_config' => 'Globalne ustawienia',
            ]
        ],
        'posting' => [
            'header' => 'Zarządzaj zajęciami',
        ],
        'global_config' => [
            'header' => 'Globalne ustawienia',
            'success' => 'Ustawienia zostały zapisane.',
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
            'questionnaire' => [
                'header' => 'Formularz zgłoszeniowy',
            ]
        ],
        'posting_list' => [
            'filter' => [
                'assigned_to' => 'Prowadzący',
                'text' => 'Szukaj',
                'age' => 'Wiek',
                'schedule' => 'Termin zajęć',
            ],
            'no_postings_found' => 'Nie znaleziono żadnych zajęć spełniających kryteria wyszukiwania.',
        ]
    ],
    'security' => [
        'logout' => 'Wyloguj się',
        'login' => 'Zaloguj się',
        'register' => 'Zarejestruj się',
        'register_success' => '<p class="text-center">Konto zostało utworzone. <br> Sprawdź swoją skrzynkę email, aby dokończyć rejestrację.</p>',
        'register_confirmed' => [
            'header' => 'Konto zostało aktywowane',
            'text' => 'Możesz teraz zalogować się na swoje konto i korzystać ze wszystkich funkcji.',
            'login' => 'Zaloguj się'
        ],
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
            'edit' => [
                'header' => 'Edytuj konto',
            ],
            'error' => [
                'already_logged_in' => 'Jesteś już zalogowany jako {name}.',
                'invalid_old_password' => 'Aktualne hasło jest nieprawidłowe.',
                'same_password' => 'Nowe hasło nie może być takie samo jak aktualne.'
            ],
            'success' => [
                'register' => 'Konto zostało utworzone. Sprawdź swoją skrzynkę email, aby dokończyć rejestrację.',
                'create_admin' => 'Konto administratora zostało utworzone. Na skrzynkę email użytkownika został wysłany link, aby dokończyć rejestrację.',
            ],
            'reset_password' => [
                'header' => 'Zresetuj hasło',
                'submit' => '
                    <i class="fas fa-key"></i>
                    Reset password
                    ',
            ],
            'email' => 'Adres email',
            'name' => 'Imię i nazwisko',
            'current_password' => 'Aktualne hasło',
            'password' => 'Hasło',
            'password_confirmation' => 'Powtórz hasło',
            'forgot_password' => [
                'link' => 'Zapomniałeś hasła?',
                'header' => 'Zapomniałeś hasła?',
                'text' => 'Podaj adres email, na który zostanie wysłany link do zresetowania hasła.',
                'submit' => '
                <i class="fas fa-key"></i>
                Wyślij link do resetowania hasła',
                'success' => 'Jeśli podany adres email istnieje w naszej bazie, na niego zostanie wysłany link do zresetowania hasła.'
            ]
        ],
        'disabled_account' => 'Twoje konto zostało wyłączone. Skontaktuj się z administratorem serwisu w celu uzyskania pomocy.',
        'logged_in_as' => 'Zalogowany jako:',
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
            'points' => 'Punkty',
            'closing_date' => 'Data zakończenia',
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
                ],
                'bonus_criteria' => [
                    'label' => 'Kryteria dodatkowe',
                    'kk3plus' => 'Krakowska Karta Rodzinna 3+'
                ]
            ],
            'schedule' => [
                'admin' => [
                    'list_header' => 'Terminy zajęć:',
                    'list_empty' => 'Brak jeszcze żadnych terminów zajęć. Dodaj termin, aby zobaczyć je tutaj.',
                    'limit' => 'Limit miejsc',
                    'time' => 'Termin zajęć',
                ]
            ],
            'form' => [
                'title' => 'Tytuł',
                'description' => 'Dodatkowe informacje',
                'assigned_to' => 'Prowadzący',
                'questionnaire' => 'Zestaw pytań',
                'success' => 'Zajęcia zostały zapisane',
                'closing_date' => 'Data zakończenia rekrutacji',
                'error' => [
                    'delete_with_candidates' => 'Nie można usunąć zajęć, które mają zgłoszonych kandydatów.',
                ],
                'limit' => 'Limit miejsc:',
            ],
            'copytext' => [
                'age_min' => 'Wiek min.',
                'age_max' => 'Wiek maks.',
                'category' => [
                    'label' => 'Kategoria',
                    'theatre' => 'Teatr',
                    'singing' => 'Śpiew',
                    'dancing' => 'Taniec',
                    'sport' => 'Sport',
                    'film' => 'Film',
                    'instrument' => 'Instrument',
                    'photography' => 'Fotografia',
                    'creative' => 'Kreatywne',
                ],
            ]
        ],
        'schedule' => [
            'form' => [
                'time' => 'Termin zajęć',
                'person_limit' => 'Limit miejsc',
                'label' => 'Terminy zajęć',
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
            'address' => 'Adres',
            'bonus_criteria' => 'Kryteria dodatkowe',
            'criteria' => [
                'additional_docs' => 'Przewodniczący komisji rekrutacyjnej może żądać dokumentów potwierdzających spełnianie kryteriów dodatkowych przez kandydata',
                'big_family' => 'Rodzina wielodzietna',
                'disability' => 'Kandydat z niepełnosprawnością',
                'disabled_parent' => 'Niepełnosprawny rodzic',
                'disabled_parents' => 'Niepełnosprawni rodzice',
                'disabled_sibling' => 'Niepełnosprawne rodzeństwo',
                'one_parent' => 'Samotne wychowywanie kandydata w rodzinie',
                'foster_family' => 'Objęcie kandydata pieczą zastępczą',
                'big_krakow_family' => 'Krakowska Karta Rodzinna 3+',
                'curatorship' => 'Kandydat jest członkiem rodziny objętej nadzorem kuratorskim lub wsparciem asystenta rodziny',
                'both_working_parents' => 'Kandydat obojga rodziców pracujących/studiujących w systemie stacjonarnym',
                'related_achievement' => 'Kandydat posiada udokumentowane osiągnięcia w dziedzinie/dyscyplinie, w której zajęcia organizowane są w placówce',
                'volunteer' => 'Kandydat prowadzi / realizuje działalność społeczną, w tym na rzecz środowiska szkolnego, w szczególności w formie wolontariatu',
                'closest_facility' => 'Placówka, o przyjęcie do której ubiega się kandydat, jest najbliżej położoną od miejsca zamieszkania kandydata publiczną placówką oświatowo-wychowawczą, prowadzącą wybrane zajęcia',
            ],
            'accept_terms' => [
                'label' => 'Akceptuję <a href="/regulamin">regulamin</a>',
            ],
            'plays_instrument' => 'Jak długo grasz na instrumencie? Na jakim?',
            'plays_sport' => 'Jak długo uprawiasz sport? Jaki?',
            'dances' => 'Jak długo tańczysz? Jaki rodzaj tańca?',
            'candidate_comment' => 'Uwagi kandydata',
        ],
        'global_config' => [
            'application_phase' => [
                'label' => 'Etap rekrutacji',
                'continuation' => 'Kontynuacja',
                'first_phase' => 'Pierwszy etap',
                'second_phase' => 'Drugi etap',
                'help' => 'Etap rekrutacji, w którym znajduje się system. <br>
                <ul>
                    <li>
                        Wpływa na dostępność funkcji i widoczność danych.
                    </li>
                    <li>
                        Zmienia wartość dodatkowych kryteriów rekrutacji.
                    </li>
                </ul>
                '
            ],
            'closing_date' => [
                'label' => 'Masowa zmiana daty zakończenia rekrutacji',
                'help' => 'Zmienia masowo datę, po której nie będzie można zgłaszać się na zajęcia.'
            ]
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
        'weekday' => [
            'monday' => 'Poniedziałek',
            'tuesday' => 'Wtorek',
            'wednesday' => 'Środa',
            'thursday' => 'Czwartek',
            'friday' => 'Piątek',
            'saturday' => 'Sobota',
            'sunday' => 'Niedziela',
        ],
        'back_to_main_page' => 'Wróć na stronę główną',
    ],
    'emails' => [
        'security' => [
            'password_reset' => [
                'subject' => 'Odzyskiwanie hasła',
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
            ],
            'create-admin-account' => [
                'subject' => 'Nowe konto administratora',
            ],
            'create-client-account' => [
                'subject' => 'Nowe konto użytkownika',
            ],
        ],
        'noreply' => '<b>Ta wiadomość została wysłana automatycznie. Prosimy na nią nie odpowiadać.</b>',
    ],
    'test' => [
        'use_test_account' => 'Użyj testowego konta',
    ]
];
