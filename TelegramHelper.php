<?php

namespace chieff\helpers;

class TelegramHelper
{
    const API_URL = 'https://api.telegram.org/bot';

    /**
     * @var string
     */
    private string $token;

    /**
     * @var string
     */
    private string $chatId;

    /**
     * @param string $token
     * @param string $chatId
     * @throws \Exception
     */
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
    public function sendMessage(string $text)
    {
        $method = 'sendMessage';

        return $this->sendRequest($method, $text);
    }

    /**
     * @param string $method
     * @param string $text
     * @return array
     */
    private function sendRequest(string $method, string $text)
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