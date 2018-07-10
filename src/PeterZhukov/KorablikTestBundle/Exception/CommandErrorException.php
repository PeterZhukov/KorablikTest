<?php

namespace PeterZhukov\KorablikTestBundle\Exception;

/**
 * <help>
 *      Класс исключения, которое будет бросаться консольной командой получения данных от поставщика
 * </help>
 */
class CommandErrorException extends \Exception
{
    /**
     * <help>
     *      Константа - код ошибки: API поставщика вернула success = false
     * </help>
     */
    const API_RESPONSE_RETURNED_FALUIRE = 1;
    /**
     * <help>
     *      Константа - код ошибки: API поставщика вернула какие-то не такие данные (любые не такие)
     * </help>
     */
    const API_RETURNED_BAD_DATA = 2;

    protected $apiData = array();

    public function getApiData()
    {
        return $this->apiData;
    }

    public function setApiData($data)
    {
        $this->apiData = $data;
    }
}