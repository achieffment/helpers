<?php

namespace chieff\helpers;

class Helper {

    /**
     * @param $var
     * @return void
     */
    public static function print_r($var)
    {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    /**
     * @param $var
     * @return void
     */
    public static function var_dump($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

    /**
     * @param string $phone
     * @param bool $empty
     * @param bool $tel
     * @return false|string
     */
    public static function makePhoneLink(string $phone, bool $empty = true, bool $tel = false)
    {
        if (!$phone)
            return $empty ? '' : false;

        preg_match_all('/[0-9]+/', $phone, $matches);

        if (!$matches || !isset($matches[0]) || !$matches[0])
            return $empty ? '' : false;

        $phone = implode('', $matches[0]);

        if (
            (mb_strlen($phone) == 11) &&
            (substr($phone, 0, 1) == '8')
        )
            $phone = '7' . substr($phone, 1);

        if (mb_strlen($phone) == 10)
            $phone = '7' . $phone;

        return ($tel ? 'tel:' : '') . '+' . $phone;
    }

    /**
     * @param $info
     * @param string $path
     * @param bool $timeShow
     * @param bool $fileAppend
     * @return void
     */
    public static function sendFileLog($info, string $path = '', bool $timeShow = true, bool $fileAppend = false)
    {
        if (is_array($info))
            $info = print_r($info, 1);
        else if (is_object($info))
            $info = var_export($info, 1);

        if ($timeShow)
            $info = date('d-m-Y H:i:s') . ' : ' . $info;

        if (!$path)
            $path = $_SERVER['DOCUMENT_ROOT'] . '/log.log';

        if ($fileAppend && !file_exists($path))
            file_put_contents($path, '');

        if ($fileAppend)
            file_put_contents($path, $info, FILE_APPEND);
        else
            file_put_contents($path, $info);
    }

    /**
     * @return string
     */
    public static function getIp() {
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    /**
     * @return bool
     */
    public static function isRobot() {
        if (
            isset($_SERVER['HTTP_USER_AGENT']) &&
            (
                (substr($_SERVER['HTTP_USER_AGENT'],'Yandex') !== false) ||
                (substr($_SERVER['HTTP_USER_AGENT'],'Googlebot') !== false) ||
                (substr($_SERVER['HTTP_USER_AGENT'],'Googlebot-Mobile') !== false) ||
                (substr($_SERVER['HTTP_USER_AGENT'],'Mail.Ru') !== false) ||
                (substr($_SERVER['HTTP_USER_AGENT'],'yahoo') !== false) ||
                (substr($_SERVER['HTTP_USER_AGENT'],'msnbot') !== false) ||
                (substr($_SERVER['HTTP_USER_AGENT'],'StackRambler') !== false)
            )
        )
            return true;
        return false;
    }

}