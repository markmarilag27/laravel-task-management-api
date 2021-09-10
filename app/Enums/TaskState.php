<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskState extends Enum
{
    public const COMPLETED  = 'completed';
    public const PENDING    = 'pending';
    public const CANCELLED  = 'cancelled';
}
