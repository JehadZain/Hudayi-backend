<?php

namespace App\Enums;

enum AvailabilitiesEnum: string
{
    const EASY = 'easy_to_find_locally';

    const HARD = 'hard_to_find_locally';

    const IMPOSSIBLE = 'not_available_locally';
}
