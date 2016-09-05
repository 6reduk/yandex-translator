<?php

namespace Pupo4ek\YandexTranslator;

class HttpClientConfig
{
    const BASE_URL = 'https://translate.yandex.net/api/v1.5/tr.json/';

    protected $requestParams = [];

    public function setProxy($proxyUrl)
    {
        $this->$requestParams['proxy'] = $proxyUrl;

        return $this;
    }

    public function toArray()
    {
        return $this->requestParams;
    }

    public function getBaseUrl()
    {
        return static::BASE_URL;
    }
}
