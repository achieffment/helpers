<?php

namespace chieff\helpers;

class SecurityHelper {

    /**
     * @param string $ciphering
     * @return void
     * @throws \Exception
     */
    private static function checkCiphering(string $ciphering)
    {
        // Check ciphering
        if (!$ciphering) {
            throw new \Exception('You must use ciphering!');
        } else if (
            ($cipherings = openssl_get_cipher_methods())
            && !in_array($ciphering, $cipherings)
        ) {
            throw new \Exception('Given ciphering is not exists!');
        }
    }

    /**
     * @param string $passphrase
     * @return string
     * @throws \Exception
     */
    private static function encodePassphrase(string $passphrase)
    {
        if (!$passphrase) {
            throw new \Exception('You must use a passphrase!');
        }

        // More security for passphrase
        $passphrase = base64_encode($passphrase);
        $passphrase = hash('sha256', $passphrase, true);
        return $passphrase;
    }

    /**
     * @param string $ciphering
     * @return string
     * @throws \Exception
     */
    private static function makeIv(string $ciphering)
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

    /**
     * @param string $data
     * @param string $ciphering
     * @param string $passphrase
     * @param mixed $options
     * @param string $iv
     * @return false|string
     * @throws \Exception
     */
    public static function encode(string $data, string $ciphering = 'aes-256-ctr', string $passphrase = '', mixed $options = 0, string $iv = '')
    {
        if (empty($data)) {
            throw new \Exception('You must give a data to encode!');
        }

        self::checkCiphering($ciphering);

        $passphrase = self::encodePassphrase($passphrase);

        // If we don't have $iv
        if (!$iv) {
            $iv = self::makeIv($ciphering);
        }

        $data = openssl_encrypt($data, $ciphering, $passphrase, $options, $iv);

        return $data;
    }

    /**
     * @param string $data
     * @param string $ciphering
     * @param string $passphrase
     * @param mixed $options
     * @param string $iv
     * @return false|string
     * @throws \Exception
     */
    public static function decode(string $data, string $ciphering = 'aes-256-ctr', string $passphrase = '', mixed $options = 0, string $iv = '')
    {
        if (empty($data)) {
            return '';
        }

        self::checkCiphering($ciphering);

        $passphrase = self::encodePassphrase($passphrase);

        // If we don't have $iv
        if (!$iv) {
            $iv = self::makeIv($ciphering);
        }

        $data = openssl_decrypt($data, $ciphering, $passphrase, $options, $iv);

        return $data;
    }

    /**
     * @param string $img_path
     * @param bool $encoded
     * @param bool $onlyContent
     * @param string $ciphering
     * @param string $passphrase
     * @param mixed $options
     * @param string $iv
     * @return false|string
     * @throws \Exception
     */
    public static function getImageContent(
        string $img_path, bool $encoded = false, bool $onlyContent = false,
        string $ciphering = 'aes-256-ctr', string $passphrase = '', mixed $options = 0, string $iv = ''
    ) {
        if (!$img_path || !file_exists($img_path)) {
            return false;
        }

        // If file encoded, we could not know mime file size
        if (!$encoded) {
            $img_size = getimagesize($img_path);
            if (
                !is_array($img_size)
                || !isset($img_size['mime'])
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

        if ($onlyContent) {
            return $img_content;
        }

        $img_content = base64_encode($img_content);

        if (!$encoded) {
            return 'data:' . $img_size['mime'] . ';base64,' . $img_content;
        }

        return 'data:;base64,' . $img_content;
    }

    /**
     * @param $img_path
     * @param $title
     * @param $alt
     * @param $loading
     * @param $encoded
     * @param $ciphering
     * @param $passphrase
     * @param $options
     * @param $iv
     * @return false|string
     * @throws \Exception
     */
    public static function getImageElement(
        $img_path, $title = '', $alt = '', $loading = '', $encoded = false,
        $ciphering = 'aes-256-ctr', $passphrase = '', $options = 0, $iv = ''
    ) {
        $content = self::getImageContent($img_path, $encoded, false, $ciphering, $passphrase, $options, $iv);

        if (!$content) {
            return false;
        }

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

        return "<img src='" . $content . "' " . $params . ">";
    }

}