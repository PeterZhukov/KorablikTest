<?php

namespace PeterZhukov\KorablikTestBundle\Services;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use PeterZhukov\KorablikTestBundle\Exception\ApiBadResponseException;


/**
 * <help>
 *      Класс, делающий запросы к API поставщика. Используется для абстрации, в нем уже используется любой backend:
 *      file_get_contents, curl и т.д.
 * </help>
 */
class ApiDownloaderService implements ApiDownloaderInterface
{
    protected $apiUrl = '';
    protected $client;


    public function __construct($apiUrl, ClientInterface $client)
    {
        $this->apiUrl = rtrim($apiUrl, '/') . '/';
        $this->client = $client;
    }

    /**
     * <help>
     *      Метод, делающий http запрос к API поставщика.
     * </help>
     */
    public function download($url)
    {
        $url = ltrim($url, '/');
        $resultUrl = $this->apiUrl . $url;
        try {
            $response = $this->client->request('get', $resultUrl);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            if (!empty($response)) {
                $httpStatusCode = $response->getStatusCode();
                if ($httpStatusCode !== 200) {
                    $exception = new ApiBadResponseException(sprintf("Api request returned bad status code: %s", $httpStatusCode), ApiBadResponseException::API_HTTP_STATUS_CODE_NOT_200, $e);
                    $exception->setApiResponse($response);
                    throw $exception;
                }
            }
            $exception = ApiBadResponseException("Api request throwed exception", ApiBadResponseException::API_GENERAL_ERROR, $e);
            $exception->setApiResponse($response);
            throw $exception;
        } catch (\Exception $e) {
            throw new ApiBadResponseException("Api request throwed exception", ApiBadResponseException::API_GENERAL_ERROR, $e);
        }

        $data = (string)$response->getBody();
        if (empty($data)) {
            $exception = new ApiBadResponseException(sprintf("Api body is empty", $response->getStatusCode()), ApiBadResponseException::API_BODY_IS_EMPTY);
            $exception->setApiResponse($response);
            throw $exception;
        }
        return $data;
    }
}