<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Status extends Enum
{
    const Inactive = 0;
    const Active   = 1;
    const Blocked  = 2;

    public static function getDescription($status): string
    {
        $value = [
            0 => 'Inativo',
            1 => 'Ativo',
            2 => 'Bloqueado',
        ];
        return $value[$status];
    }
}
