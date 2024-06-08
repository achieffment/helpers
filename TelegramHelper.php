<?php

namespace chieff\helpers;

use ParagonIE\Sodium\Core\Curve25519\Ge\P1p1;

class TelegramHelper
{
    const API_URL = 'https://api.telegram.org/bot';

    private string $token;

    private string $chatId;

    public function __construct(string $token, string $chatId)
    {
        if (!$token) {
            throw new \Exception('Token is empty');
        }
        if (!$chatId) {
            throw new \Exception('Chat id is empty');
        }

        $this->token = $token;
        $this->chatId = $chatId;
    }

    /**
     * @param string $text
     * @return array
     */
    public function sendMessage(string $text): array
    {
        $method = 'sendMessage';

        return $this->sendRequest($method, $text);
    }

    /**
     * @param string $method
     * @param string $text
     * @return array
     */
    private function sendRequest(string $method, string $text): array
    {
        $url = self::API_URL . $this->token . '/' . $method;
        $params = [
            'chat_id' => $this->chatId,
            'text' => $text,
            'parse_mode' => 'html'
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
}