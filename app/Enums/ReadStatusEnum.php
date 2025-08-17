<?php

namespace App\Enums;

use App\Enums\EnumToArray;

enum ReadStatusEnum: int
{
    use EnumToArray;

    case READ = 1;
    case UNREAD = 0;

    public function title(): string
    {
        return match ($this) {
            self::READ => 'Read',
            self::UNREAD => 'Unread',
        };
    }
}
