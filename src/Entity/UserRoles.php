<?php

namespace App\Entity;

enum UserRoles: string
{
    case SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    case ADMIN = 'ROLE_ADMIN';
    case BASE_USER = 'ROLE_USER';
    case CLIENT = 'ROLE_CLIENT';
}
