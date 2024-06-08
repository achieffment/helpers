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
        if (!$value) {
            return false;
        }

        $value = strip_tags($value);
        if (!$value) {
            return false;
        }

        $value = htmlspecialchars($value);
        if (!$value) {
            return false;
        }

        $value = trim($value);
        if (!$value) {
            return false;
        }

        if (
            $length
            && !$is_number
            && (mb_strlen($value) > $length)
        ) {
            return false;
        }

        if (
            $is_number
            && !is_numeric($value)
        ) {
            return false;
        }

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
        if ($value) {
            $value = strip_tags($value);
        }

        if ($value) {
            $value = htmlspecialchars($value);
        }

        if ($value) {
            $value = trim($value);
        }

        if (
            $value
            && $length
            && !$is_number
            && (mb_strlen($value) > $length)
        ) {
            return '';
        }

        if (
            $value
            && $is_number
            && !is_numeric($value)
        ) {
            return '';
        }

        return $value;
    }

    /**
     * @param string $phone
     * @param int $length
     * @return bool
     */
    public static function validatePhone(string $phone, int $length = 11)
    {
        if (!$phone) {
            return false;
        }

        preg_match_all('/[0-9]+/', $phone, $matches);

        if (!$matches || !isset($matches[0]) || !$matches[0]) {
            return false;
        }

        $phone = implode('', $matches[0]);

        if (mb_strlen($phone) != $length) {
            return false;
        }

        return true;
    }

    /**
     * @param string $ip
     * @param bool $portCheck
     * @param bool $portEmpty
     * @return bool
     */
    public static function validateIp(string $ip, bool $portCheck = true, bool $portEmpty = true)
    {
        if (!$ip) {
            return false;
        }

        $regexp = '/^(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}|[0-9])){3}';

        if ($portCheck && !$portEmpty) {
            $regexp .= ':[0-9]{1,6}$/';
        } else if ($portCheck && $portEmpty) {
            $regexp .= '(|:[0-9]{1,6})$/';
        } else {
            $regexp .= '$/';
        }

        if (preg_match('', $ip) !== 1) {
            return false;
        }

        return true;
    }

}