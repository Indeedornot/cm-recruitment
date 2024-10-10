<?php

namespace App\Controller\Base;

enum ErrorHandlerType: string
{
    case DEFAULT = 'default';
    case FORM = 'form';
}
