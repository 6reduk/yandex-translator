<?php

namespace Pupo4ek\YandexTranslator;

use GuzzleHttp\Client as HttpClient;
use Pupo4ek\YandexTranslator\Exceptions\PropertyNotSettingExcecption;
use Pupo4ek\YandexTranslator\Exceptions\RequestFailedException;
use Pupo4ek\YandexTranslator\Exceptions\ApiException;
use Pupo4ek\YandexTranslator\Exceptions\BrokenResponseData;
use Pupo4ek\YandexTranslator\ApiConfig;
use Pupo4ek\YandexTranslator\HttpClientConfig;


class Translator
{
    protected $httpClient;
    protected $apiConfig;
    protected $rawResponse;

    public function setApiConfig(ApiConfig $config)
    {
        $this->apiConfig = $config;

        return $this;
    }

    public function getApiConfig()
    {
        if (!$this->apiConfig) {
            throw new PropertyNotSettingExcecption('Missing api config.');
        }

        return $this->apiConfig;
    }

    public function setHttpClientConfig(HttpClientConfig $config)
    {
        $this->httpClientConfig = $config;

        return $this;
    }

    public function getHttpClientConfig()
    {
        if (!$this->httpClientConfig) {
            throw new PropertyNotSettingExcecption('Missing http client config.');
        }

        return $this->httpClientConfig;
    }

    public function init()
    {
        $this->httpClient = new HttpClient([
            'base_uri' => $this->getHttpClientConfig()->getBaseUrl()
        ]);

        return $this;
    }

    public function supportedLanguages($culture = null)
    {
        return $this->execute(
            'getLangs', [
                'ui' => $culture,
                'key' => $this->apiConfig->getKey()
            ]);
    }

    public function translate($text, $language=null)
    {
        $params = [
            'text' => $text
        ];

        if ($language) {
            $params['lang'] = $language;
        }

        $data = $this->execute('translate', $params);

        return new Translation($text, $data['text'], $data['lang']);
    }

    private function getHttpClientParams()
    {
        return $this->httpClientConfig->toArray();
    }

    private function getApiParams()
    {
        return $this->apiConfig->toArray();
    }

    private function getRequestParams()
    {
        $params = $this->getHttpClientParams();
        $params = array_merge($params, ['form_params' => $this->getApiParams()]);

        return $params;
    }

    private function parseApiResponse($responseBody)
    {
        $parsedData = json_decode($responseBody, true);

        if (!$parsedData) {
            throw new BrokenResponseData(sprintf(
                'Broken response json data: %s. Reason: %s',
                $responseBody,
                json_last_error_msg()
            ));
        }

        return $parsedData;
    }

    private function checkApiResponseCode($data)
    {
        //TODO add handlers for all api response codes
        if (isset($data['code']) && $data['code'] > 200) {
            throw new ApiException(sprintf(
                'Api error code: %s, message: %s',
                $data['message'],
                $data['code']
                ));
        }
    }

    /**
     * @param string $uri
     * @param array  $params
     *
     * @throws Exception
     * @return array
     */
    protected function execute($uri, array $params)
    {
        $requestParams = $this->getRequestParams();
        $requestParams['form_params'] = array_merge($requestParams['form_params'], $params);

        $this->rawResponse = $this->httpClient->request(
            'POST',
            $uri,
            $requestParams
        );

        if ($this->rawResponse->getStatusCode() !== 200 ) {
            throw new RequestFailedException(sprintf(
                'Api request failed. Response code: %s, reason: %s',
                $this->rawResponse->getStatusCode(),
                $this->rawResponse->getReasonPhrase()
            ));
        }

        $data = $this->parseApiResponse($this->rawResponse->getBody());

        $this->checkApiResponseCode($data);

        return $data;
    }
}
