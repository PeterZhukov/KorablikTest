<?php

namespace PeterZhukov\KorablikTestBundle\Services;

use PeterZhukov\KorablikTestBundle\Exception\XmlResponseParserException;
use PeterZhukov\KorablikTestBundle\Services\ResponseParserInterface;

/**
 * <help>
 *      Класс преобразующий XML в массив. Сейчас используется для работы с API поставщика.
 * <help>
 */
class XmlResponseParser implements ResponseParserInterface
{
    public function parse($string)
    {
        $use_errors = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($string);
        if ($xml === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            libxml_use_internal_errors($use_errors);
            $exception = new XmlResponseParserException('Unable to parse xml', XmlResponseParserException::UNABLE_TO_PARSE_XML);
            $exception->setData($string);
            $additionalData = array(
                'errors' => $errors,
            );
            $exception->setAdditionalData($additionalData);
            throw $exception;
        }
        libxml_clear_errors();
        libxml_use_internal_errors($use_errors);
        $json = json_encode((array)$xml);
        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE) {
            $exception = new XmlResponseParserException('Unable to convert xml to array', XmlResponseParserException::UNABLE_TO_CONVERT_XML_TO_ARRAY);
            $exception->setData($string);
            $additionalData = array(
                'code' => $error,
                'message' => json_last_error_msg(),
            );
            $exception->setAdditionalData($additionalData);
            throw $exception;
        }
        $array = json_decode($json, true);
        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE) {
            $exception = new XmlResponseParserException('Unable to convert xml to array', XmlResponseParserException::UNABLE_TO_CONVERT_XML_TO_ARRAY);
            $exception->setData($string);
            $additionalData = array(
                'code' => $error,
                'message' => json_last_error_msg(),
            );
            $exception->setAdditionalData($additionalData);
            throw $exception;
        }
        if (!empty($array['success'])) {
            if ($array['success'] === 'true') {
                $array['success'] = true;
            } elseif ($array['success'] === 'false') {
                $array['success'] = false;
            }
        }
        return $array;
    }
}