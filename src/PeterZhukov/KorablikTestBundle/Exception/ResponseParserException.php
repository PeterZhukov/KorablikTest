<?php

namespace PeterZhukov\KorablikTestBundle\Exception;

/**
 * <help>
 *      Класс исключения, которое будет бросать ResponseParserInterface, например JsonResponseParser будет бросать
 *      исключение, если json_last_error вернет что-то отличное от JSON_ERROR_NONE
 * </help>
 */
class ResponseParserException extends \Exception
{
    const GENERAL_PARSER_ERROR = 0;
    /*
     * <help>
     *      Данные, пришедшие в парсер
     * </help>
     */
    protected $data;

    /**
     * <help>
     *      Дополнительные данные, например для парсера JSON'а будет возвращена информация от json_last_error и
     *      json_last_error_msg
     * <help>
     */
    protected $additionalData;

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    public function setAdditionalData($additionalData)
    {
        $this->additionalData = $additionalData;
    }
}