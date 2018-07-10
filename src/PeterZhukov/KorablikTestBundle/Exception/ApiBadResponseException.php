<?php

namespace PeterZhukov\KorablikTestBundle\Exception;


use Psr\Http\Message\ResponseInterface;

/**
 * <help>
 *      Класс исключения, которое будет бросаться HTTP клиентом, когда API поставщика ответило неправильно
 * </help>
 */
class ApiBadResponseException extends \Exception
{
    /**
     * <help>
     *      Константа - код ошибки: код ответа API поставщика != 200
     * </help>
     */
    const API_HTTP_STATUS_CODE_NOT_200 = 1;
    /**
     * <help>
     *      Константа - код ошибки: тело ответа API поставщика (там где должны быть товары) - пустое
     * </help>
     */
    const API_BODY_IS_EMPTY = 2;
    /**
     * <help>
     *      Константа - код ошибки: в целом какя-то проблема с API постащика (в дальнейшем буду добавляться новвые
     *      специальное коды ошибок, а все что не в специальных кода ошибок - будет использоваться этот код.
     * </help>
     */
    const API_GENERAL_ERROR = 3;


    protected $apiResponse;

    public function getApiResponse()
    {
        return $this->apiResponse;
    }

    public function setApiResponse(ResponseInterface $response)
    {
        $this->apiResponse = $response;
    }
}