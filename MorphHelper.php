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
     * @param int $count
     * @param string $string
     * @return string
     */
    public static function getPluralize(int $count, string $string)
    {
        if (($count < 0) || !$string)
            return '';

        return morphos\Russian\pluralize($count, $string);
    }

    /**
     * @param int $number
     * @param string $morphCase
     * @return string
     */
    public static function getCardinalNumber(int $number, string $morphCase = 'именительный')
    {
        if ($number < 0)
            return '';

        return morphos\Russian\CardinalNumeralGenerator::getCase($number, $morphCase);
    }

    /**
     * @param int $number
     * @param string $morphCase
     * @return string
     */
    public static function getOrdinalNumber(int $number, string $morphCase = 'именительный')
    {
        if ($number < 0)
            return '';

        return morphos\Russian\OrdinalNumeralGenerator::getCase($number, $morphCase);
    }

    /**
     * @param $time
     * @return string
     */
    public static function getTime($time)
    {
        if (!$time)
            return '';

        return morphos\Russian\TimeSpeller::spellDifference($time, morphos\TimeSpeller::DIRECTION);
    }

}