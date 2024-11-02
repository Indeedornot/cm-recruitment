<?php

namespace App\Security\Form;

enum UserFormMode
{
    case CREATE;
    case EDIT;
    case PASSWORD_CHANGE;
}
