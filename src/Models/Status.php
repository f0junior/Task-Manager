<?php

declare(strict_types=1);

namespace App\Models;

enum Status: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';
}