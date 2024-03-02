<?php

namespace chieff\helpers;

class ValidatorHelper {

    /**
     * @param string $value
     * @param int $length
     * @param bool $is_number
     * @return false|float|int|string
     */
    public static function validate(string $value, int $length = 0, bool $is_number = false)
    {
        if (!$value)
            return false;

        $value = strip_tags($value);
        if (!$value)
            return false;

        $value = htmlspecialchars($value);
        if (!$value)
            return false;

        $value = trim($value);
        if (!$value)
            return false;

        if (
            $length &&
            !$is_number &&
            (mb_strlen($value) > $length)
        )
            return false;

        if (
            $is_number &&
            !is_numeric($value)
        )
            return false;

        return $value;
    }

    /**
     * @param string $value
     * @param int $length
     * @param bool $is_number
     * @return false|float|int|string
     */
    public static function validateEmpty(string $value, int $length = 0, bool $is_number = false)
    {
        if ($value)
            $value = strip_tags($value);

        if ($value)
            $value = htmlspecialchars($value);

        if ($value)
            $value = trim($value);

        if (
            $value &&
            $length &&
            !$is_number &&
            (mb_strlen($value) > $length)
        )
            return '';

        if (
            $value &&
            $is_number &&
            !is_numeric($value)
        )
            return '';

        return $value;
    }

}