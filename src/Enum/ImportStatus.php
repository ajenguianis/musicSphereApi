<?php

namespace App\Enum;

enum ImportStatus: string
{
    case Pending = 'Pending';
    case InProgress = 'In Progress';
    case Completed = 'Completed';
    case Failed  = 'Failed';
}
