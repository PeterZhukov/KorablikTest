<?php

namespace PeterZhukov\KorablikTestBundle\Services;

use PeterZhukov\KorablikTestBundle\Exception\ResponseParserException;
use PeterZhukov\KorablikTestBundle\Services\ResponseParserInterface;

/**
 * <help>
 *      Класс преобразующий JSON в массив. Сейчас используется для работы с API поставщика.
 * <help>
 */
class JsonResponseParser implements ResponseParserInterface
{
    public function parse($string)
    {
        $data = json_decode($string, true);
        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE) {
            $exception = new ResponseParserException('Json parser unable to parse data', ResponseParserException::GENERAL_PARSER_ERROR);
            $exception->setData($string);
            $additionalData = array(
                'code' => $error,
                'message' => json_last_error_msg(),
            );
            $exception->setData($additionalData);
            throw $exception;
        }
        return $data;
    }
}