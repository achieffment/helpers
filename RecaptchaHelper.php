<?php

namespace chieff\helpers;

use chieff\helpers\Helper;

class RecaptchaHelper {

    /**
     * @param string $secret
     * @param string $token
     * @param string $remoteip
     * @return false|mixed
     */
    public static function reCAPTCHAV3Send(string $secret, string $token, string $remoteip = '') {
        if (!$secret || !$token)
            return false;

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $params = [
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => $remoteip ? $remoteip : Helper::getIp(),
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        return json_decode($response, true);
    }

    /**
     * @param string $secret
     * @param string $token
     * @param string $remoteip
     * @return bool
     */
    public static function reCAPTCHAV3Validate(string $secret, string $token, string $remoteip = '')
    {
        if (!$token)
            return false;

        $response = self::reCAPTCHAV3Send($secret, $token, $remoteip);

        if (
            !$response ||
            !is_array($response) ||
            !isset($response['success']) ||
            !$response["success"]
        )
            return false;

        return true;
    }

    /**
     * @param string $public
     * @param string $selector
     * @return string
     */
    public static function reCAPTCHAV3JS(string $public, string $selector = '#rcv_token')
    {
        if (!$public || !$selector)
            return '';

        return '
        <script src="https://www.google.com/recaptcha/api.js?onload=ReCaptchaCallbackV3&render=' . $public .  '"></script>
        <script>
            var ReCaptchaCallbackV3 = function() {
                grecaptcha.ready(function() {
                    grecaptcha.reset = grecaptchaExecute;
                    grecaptcha.reset();
                });
            };
            function grecaptchaExecute() {
                grecaptcha.execute("' . $public . '", { action: "submit" }).then(function(token) {
                    var fieldToken = document.querySelector("' . $selector . '");
                    if (fieldToken !== undefined && fieldToken !== "undefined" && fieldToken !== null)
                        fieldToken.value = token;
                });
            };
            setInterval(function() {
                grecaptcha.reset();
            }, 60000);
        </script>
        ';
    }

    /**
     * @param string $public
     * @return string
     */
    public static function reCAPTCHAV3JSOnlyAPI(string $public)
    {
        if (!$public)
            return '';

        return '
            <script src="https://www.google.com/recaptcha/api.js?onload=ReCaptchaCallbackV3&render=' . $public .  '"></script>
        ';
    }

    /**
     * @param string $public
     * @param string $selector
     * @return string
     */
    public static function reCAPTCHAV3JSOnlyScript(string $public, string $selector = '#rcv_token')
    {
        if (!$public || !$selector)
            return '';

        return '
        <script>
            var ReCaptchaCallbackV3 = function() {
                grecaptcha.ready(function() {
                    grecaptcha.reset = grecaptchaExecute;
                    grecaptcha.reset();
                });
            };
            function grecaptchaExecute() {
                grecaptcha.execute("' . $public . '", { action: "submit" }).then(function(token) {
                    var fieldToken = document.querySelector("' . $selector . '");
                    if (fieldToken !== undefined && fieldToken !== "undefined" && fieldToken !== null)
                        fieldToken.value = token;
                });
            };
            setInterval(function() {
                grecaptcha.reset();
            }, 60000);
        </script>
        ';
    }
}