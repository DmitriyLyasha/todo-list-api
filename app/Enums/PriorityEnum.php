<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self PRIORITY_1()
 * @method static self PRIORITY_2()
 * @method static self PRIORITY_3()
 * @method static self PRIORITY_4()
 * @method static self PRIORITY_5()
 */
class PriorityEnum extends Enum
{
    const MAP_VALUE_TO_NAME = [
        1 => 'PRIORITY_1',
        2 => 'PRIORITY_2',
        3 => 'PRIORITY_3',
        4 => 'PRIORITY_4',
        5 => 'PRIORITY_5',
    ];

    // Додайте метод для отримання значення за замовчуванням (значення для нового завдання)
    public static function defaultValue(): self
    {
        return self::PRIORITY_1();
    }
}
