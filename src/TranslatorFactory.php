<?php

namespace Pupo4ek\YandexTranslator;


class TranslatorFactory
{
    public static function create($apiKey, $translateDirection=null, $proxyUrl=null)
    {
        $apiConfig = new ApiConfig;
        $apiConfig->setKey($apiKey);

        if ($translateDirection) {
            $apiConfig->setTranslateDirection($translateDirection);
        }

        $httpClientConfig = new HttpClientConfig;

        if ($proxyUrl) {
            $httpClientConfig->setProxy($proxyUrl);
        }

        $translator = new Translator;

        return $translator->setHttpClientConfig($httpClientConfig)
            ->setApiConfig($apiConfig)
            ->init();
    }
}
