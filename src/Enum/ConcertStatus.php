<?php

namespace App\Enum;

enum ConcertStatus: string
{
    case Planned = 'Planned';
    case Confirmed = 'Confirmed';
    case Cancelled = 'Cancelled';
}