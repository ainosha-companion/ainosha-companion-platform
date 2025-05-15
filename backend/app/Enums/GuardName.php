<?php

declare(strict_types=1);

namespace App\Enums;

enum GuardName: string
{
    case API = 'api';
    case WEB = 'web';
}