<?php

namespace Helpers;

class Curl
{
    private $ch;
    private $ya_key = 'dict.1.1.20231122T145631Z.70623755fc0fd9b4.b09afd94e72c5804cd2ad2fbc16a570730d1f522';
    private $url = 'https://dictionary.yandex.net/api/v1/dicservice.json/lookup?';

    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
    }

    public function getWord($text)
    {
        $result = [];

        $params = array(
            'key' => $this->ya_key,
            'text' => $text,
            'lang' => 'ru-ru'
        );

        curl_setopt($this->ch, CURLOPT_URL, $this->url . http_build_query($params));
        $result_curl = json_decode(curl_exec($this->ch), true);

        if(empty($result_curl['def'])) {
            $result[] = 'Слова <span>[' . $text . ']</span> не существует!';
        }

        return $result;
    }
};