<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ContentType extends Enum
{
    public const EXCEL  = 'application/vnd.ms-excel';
    public const CSV    = 'text/csv';
    public const JSON   = 'application/json';
}
