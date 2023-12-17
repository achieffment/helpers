<?php

namespace chieff\helpers;

class SecurityHelper {

    private static function checkCiphering($ciphering)
    {
        // Check ciphering
        if (!$ciphering) {
            throw new \Exception('You must use ciphering!');
        } else if (
            ($cipherings = openssl_get_cipher_methods()) &&
            !in_array($ciphering, $cipherings)
        ) {
            throw new \Exception('Given ciphering is not exists!');
        }
    }

    private static function encodePassphrase($passphrase)
    {
        if (!$passphrase) {
            throw new \Exception('You must use a passphrase!');
        }
        // More security for passphrase
        $passphrase = base64_encode($passphrase);
        $passphrase = hash('sha256', $passphrase, true);
    }

    private static function makeIv($ciphering)
    {
        $iv_length = openssl_cipher_iv_length($ciphering); // Get length of cipher

        // We can use simple methods:
        // $iv = openssl_random_pseudo_bytes($iv_length); // It will give us random bytes, so encrypted value will change
        // $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0); // Simple bytes array

        $bytes = unpack('C*', $iv_length); // Make bytes array from string
        if (
            ($bytes_count = iconv_strlen(implode('', $bytes))) < 16 // It must be a string of 16 bytes
        ) {
            while ($bytes_count < 16) {
                $bytes = array_merge(...array_map(null, $bytes, $bytes)); // Merge arrays for more bytes
                $bytes_count = iconv_strlen(implode('', $bytes));
            }
        }
        // It can not be a string more than 16 bytes
        if ($bytes_count > 16) {
            throw new \Exception('Can not build iv, use your own or simple examples');
        }

        $iv = implode('', $bytes);

        return $iv;
    }

    public static function encode($data, $ciphering = 'aes-256-ctr', $passphrase = '', $options = 0, $iv = '')
    {
        if (!$data) {
            throw new \Exception('You must give a data to encode!');
        }

        self::checkCiphering($ciphering);

        $passphrase = self::encodePassphrase($passphrase);

        // If we don't have $iv
        if (!$iv) {
            $iv = self::makeIv($ciphering);
        }

        $encoded = openssl_encrypt($data, $ciphering, $passphrase, $options, $iv);

        return $encoded;
    }

    public static function decode($encoded, $ciphering = 'aes-256-ctr', $passphrase = '', $options = 0, $iv = '')
    {
        if (!$encoded) {
            throw new \Exception('You must give a data to decode!');
        }

        self::checkCiphering($ciphering);

        $passphrase = self::encodePassphrase($passphrase);

        // If we don't have $iv
        if (!$iv) {
            $iv = self::makeIv($ciphering);
        }

        $decoded = openssl_decrypt($encoded, $ciphering, $passphrase, $options, $iv);

        return $decoded;
    }

    public static function getImageContent(
        $img_path, $encoded = false,
        $ciphering = 'aes-256-ctr', $passphrase = '', $options = 0, $iv = ''
    ) {
        if (!file_exists($img_path)) {
            return false;
        }

        // If file encoded, we could not know mime file size
        if (!$encoded) {
            $img_size = getimagesize($img_path);
            if (
                !is_array($img_size) ||
                !isset($img_size['mime'])
            ) {
                return false;
            }
        }

        $img_content = file_get_contents($img_path);
        if ($encoded) {
            $img_content = self::decode($img_content, $ciphering, $passphrase, $options, $iv);
        }

        if (!$img_content) {
            return false;
        }
        $img_content = base64_encode($img_content);

        if (!$encoded) {
            return 'data:' . $img_size['mime'] . ';base64,' . $img_content;
        }

        return 'data:;base64,' . $img_content;
    }

    public static function getImageElement(
        $img_path, $title = '', $alt = '', $loading = '', $encoded = false,
        $ciphering = 'aes-256-ctr', $passphrase = '', $options = 0, $iv = ''
    ) {
        $params = '';
        if ($title) {
            $title = "title='" . $title . "' ";
            $params = $title;
        }
        if ($alt) {
            $alt = "alt='" . $alt . "' ";
            $params .= $alt;
        }
        if ($loading) {
            $loading = "loading='" . $loading . "'";
            $params .= $loading;
        }
        $content = self::getImageContent($img_path, $encoded, $ciphering, $passphrase, $options, $iv);
        if (!$content) {
            return false;
        }
        return "<img src='" . $content . "' " . $params . ">";
    }

}