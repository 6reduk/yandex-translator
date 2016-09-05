<?php

namespace Pupo4ek\YandexTranslator;


class Translation
{
    /**
     * @var string|array
     */
    protected $source;

    /**
     * @var string|array
     */
    protected $result;

    /**
     * @var string
     */
    protected $sourceLanguage;

    /**
     * @var string
     */
    protected $destinationLanguage;

    /**
     * @param string|array $source   The source text
     * @param string|array $result   The translation result
     * @param string       $language Translation language
     */
    public function __construct($source, $result, $language)
    {
        $this->source = $source;
        $this->result = $result;
        list($this->sourceLanguage, $this->destinationLanguage) = explode('-', $language);
    }

    /**
     * @return string|array The source text
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return array|string The result text
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string The source language.
     */
    public function getSourceLanguage()
    {
        return $this->sourceLanguage;
    }

    /**
     * @return string The translation language.
     */
    public function getResultLanguage()
    {
        return $this->destinationLanguage;
    }

    /**
     * @return string The translation text.
     */
    public function __toString()
    {
        if (is_array($this->result)) {
            return join(' ', $this->result);
        }
        return (string) $this->result;
    }
}
