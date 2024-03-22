<?php

namespace chieff\helpers;

class MorphHelper {

    /**
     * @param string $name
     * @param string $morphCase
     * @return string
     */
    public static function getName(string $name, string $morphCase = 'предложный')
    {
        if (!$name)
            return '';

        return morphos\Russian\inflectName($name, $morphCase);
    }

    /**
     * @param string $city
     * @param string $morphCase
     * @return mixed|string|void
     * @throws \Exception
     */
    public static function getCity(string $city, string $morphCase = 'предложный')
    {
        if (!$city)
            return '';

        return \morphos\Russian\GeographicalNamesInflection::getCase($city, $morphCase);
    }

    /**
     * @param int|string $count
     * @param string $word
     * @return string
     */
    public static function getPluralize(int|string $count, string $word)
    {
        if (
            !is_numeric($count) ||
            ($count < 0) ||
            !$word
        )
            return '';

        return morphos\Russian\pluralize($count, $word);
    }

    /**
     * @param int|string $number
     * @param string $morphCase
     * @return string
     */
    public static function getCardinalNumber(int|string $number, string $morphCase = 'именительный')
    {
        if (
            !is_numeric($number) ||
            $number < 0
        )
            return '';

        return morphos\Russian\CardinalNumeralGenerator::getCase($number, $morphCase);
    }

    /**
     * @param int|string $number
     * @param string $morphCase
     * @return string
     */
    public static function getOrdinalNumber(int|string $number, string $morphCase = 'именительный')
    {
        if (
            !is_numeric($number) ||
            $number < 0
        )
            return '';

        return morphos\Russian\OrdinalNumeralGenerator::getCase($number, $morphCase);
    }

    /**
     * @param int|string $time
     * @return string
     */
    public static function getTime(int|string $time)
    {
        if (
            !$time ||
            !is_numeric($time)
        )
            return '';

        return morphos\Russian\TimeSpeller::spellDifference($time, morphos\TimeSpeller::DIRECTION);
    }

}