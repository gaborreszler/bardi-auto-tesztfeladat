<?php

namespace App\Enums;

enum SeatStatus: string
{
    case FREE = 'free';
    case RESERVED = 'reserved';
    case TAKEN = 'taken';
}
