<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ExportType extends Enum
{
    public const EXCEL  = 'excel';
    public const CSV    = 'csv';
    public const JSON   = 'json';
}
