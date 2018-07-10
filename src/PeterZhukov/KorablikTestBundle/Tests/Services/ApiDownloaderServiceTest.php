<?php

namespace PeterZhukov\KorablikTestBundle\Tests\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PeterZhukov\KorablikTestBundle\Exception\ApiBadResponseException;
use PeterZhukov\KorablikTestBundle\Services\ApiDownloaderService;

class ApiDownloaderServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testApiDownloaderServiceReturnsNormalDataInNormalSituation(){
        $body = 'some_data';
        $response = new Response(200, array(), $body);
        $handler = new MockHandler([$response]);
        $httpClient = new Client(['handler' => $handler]);
        $apiDownloaderService = new ApiDownloaderService('http://example.com', $httpClient);
        $response = $apiDownloaderService->download('/test');
        $this->assertEquals($body, $response);
    }

    public function testApiDownloaderServiceThrowsErrorOnNot200HttpStatus(){
        $this->expectException(ApiBadResponseException::class);

        $body = 'some_data';

        $request = new Request('GET', 'http://example.com');
        $response = new Response(404, array(), $body);
        $exception = new ClientException('404 error', $request, $response);
        $handler = new MockHandler([$exception]);
        $httpClient = new Client(['handler' => $handler]);
        $apiDownloaderService = new ApiDownloaderService('http://example.com', $httpClient);
        try{
            $response = $apiDownloaderService->download('/test');
        } catch (ApiBadResponseException $e){
            $response = $e->getApiResponse();
            $this->assertNotEmpty($response);
            $this->assertEquals(ApiBadResponseException::API_HTTP_STATUS_CODE_NOT_200, $e->getCode());
            throw $e;
        }
    }

    public function testApiDownloaderServiceThrowsExceptionOnEmptyResponse(){
        $this->expectException(ApiBadResponseException::class);
        $body = '';
        $response = new Response(404, array(), $body);
        $handler = new MockHandler([$response]);
        $httpClient = new Client(['handler' => $handler]);
        $apiDownloaderService = new ApiDownloaderService('http://example.com', $httpClient);
        try{
            $response = $apiDownloaderService->download('/test');
        } catch (ApiBadResponseException $e){
            $response = $e->getApiResponse();
            $this->assertNotEmpty($response);
            $this->assertEquals(ApiBadResponseException::API_BODY_IS_EMPTY, $e->getCode());
            throw $e;
        }
    }
}
