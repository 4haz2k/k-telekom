<?php

namespace App\Http\Services;


class MaskService
{
    /**
     * Паттерн маски
     *
     * @var string[]
     */
    protected static $pattern = [
        'N' => '[0-9]',
        'A' => '[A-Z]',
        'a' => '[a-z]',
        'X' => '[A-Z0-9]',
        'Z' => '[\-_\@]'
    ];

    /**
     * Проверка серийного номера на соотвестветствие маски
     *
     * @param string $serialNumber
     * @param string $mask
     * @return bool
     */
    public static function checkSerial(string $serialNumber, string $mask): bool
    {
        if (strlen($serialNumber) != strlen($mask))
            return false;

        $maskChars = str_split($mask);

        $result = '';

        foreach ($maskChars as $maskChar)
            $result .= self::$pattern[$maskChar];

        return preg_match('/^' . $result . '/', $serialNumber) > 0;
    }


}
