<?php

namespace App\Services;

class SevenTelegramApi {

    public $curl;

    public $API = 'https://api.telegram.org/bot';

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function api($method, $params)
    {
        $url = $this->API . env('SEVEN_TELEGRAM_BOT_API_KEY') . '/' . $method;

        return $this->do($url, $params);
    }

    private function do(string $url, array $params = [])
    {
        $params = json_encode($params);

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', 'Content-Length: ' . strlen($params),
        ]);
        $result = json_decode(curl_exec($this->curl), TRUE);
        return $result;
    }

    public function isTyping($chat_id)
    {
        $this->api('sendChatAction', ['chat_id' => $chat_id, 'action' => 'typing']);
    }

    public function sendMessage(string $text, $chat_id)
    {
        $this->isTyping($chat_id);
        $this->api('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
        ]);
    }

    public function deleteMessage($chat_id, $message_id)
    {
        $this->api('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);
    }

    public function __destruct()
    {
        $this->curl = curl_close($this->curl);
    }

}