<?php

namespace Pupo4ek\YandexTranslator;

class ApiConfig
{
    const RESPONSE_DATA_FORMAT_PLAIN = 'plain';
    const RESPONSE_DATA_FORMAT_HTML = 'html';

    private $key;
    private $params = [
        'options' => 0,
        'format' => static::RESPONSE_DATA_FORMAT_PLAIN,
    ];

    public function getKey()
    {
        if (!$this->key) {
            throw new PropertyNotSettingExcecption('Api key not setting');
        }

        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function toArray()
    {
        return array_merge(['key' => $this->getKey()], $this->params);
    }

    public function setOptions($options)
    {
        $this->params['options'] = $options;
        return $this;
    }

    public function setTranslateDirection($lang)
    {
        $this->params['lang'] = $lang;
        return $this;
    }
}
