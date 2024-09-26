<?php

namespace App\Entity;

enum UserRoles: string
{
    case SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    case ADMIN = 'ROLE_ADMIN';
    case BASE_USER = 'ROLE_USER';
    case CLIENT = 'ROLE_CLIENT';

    public function getLabel(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::BASE_USER => 'User',
            self::CLIENT => 'Client',
        };
    }
}
