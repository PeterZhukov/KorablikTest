<?php

namespace PeterZhukov\KorablikTestBundle\Tests\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PeterZhukov\KorablikTestBundle\Command\GetProductsCommand;
use PeterZhukov\KorablikTestBundle\Exception\CommandErrorException;
use PeterZhukov\KorablikTestBundle\Services\ApiDownloaderService;
use PeterZhukov\KorablikTestBundle\Services\JsonResponseParser;
use Symfony\Bridge\PhpUnit\TextUI\Command;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GetProductsCommandTest extends KernelTestCase
{
    protected $products = array(
        "data" => array(
            "products" => array(
                "vendor_code" => "BDK123L",
                "name" => "Товар 1",
                "price" => 100,
                "features" => array(
                    "some_feature1" => 100,
                    "some_feature2" => 'some text',
                ),
            ),
        ),
        'success' => true,
    );

    protected $productsError = array(
        "data" => array(
            "message" => "Get out of here",
            "code" => "GENERAL_ERROR",
        ),
        'success' => false,
    );


    public function testGetProductsCommandReturnsNormalDataInNormalSituation()
    {
        $body = json_encode($this->products);

        $response = new Response(200, array(), $body);
        $handler = new MockHandler([$response]);
        $httpClient = new Client(['handler' => $handler]);
        $apiDownloaderService = new ApiDownloaderService('http://example.com', $httpClient);
        $parser = new JsonResponseParser();
        $command = new GetProductsCommand(null, $apiDownloaderService, $parser);
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->add($command);

        $command = $application->find('zhukov:get-products');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Command completed successfully.', $output);
    }

    public function testGetProductsCommandPrintsMesasgeAndThrowsExceptionOnFailedButValidApiResponse()
    {
        $this->expectException(CommandErrorException::class);

        $body = json_encode($this->productsError);

        $response = new Response(200, array(), $body);
        $handler = new MockHandler([$response]);
        $httpClient = new Client(['handler' => $handler]);
        $apiDownloaderService = new ApiDownloaderService('http://example.com', $httpClient);
        $parser = new JsonResponseParser();
        $command = new GetProductsCommand(null, $apiDownloaderService, $parser);
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->add($command);

        $command = $application->find('zhukov:get-products');
        $commandTester = new CommandTester($command);
        try {
            $commandTester->execute(array(
                'command' => $command->getName(),
            ));
        } catch (CommandErrorException $e) {
            $this->assertEquals(CommandErrorException::API_RESPONSE_RETURNED_FALUIRE, $e->getCode());
            $output = $commandTester->getDisplay();
            $this->assertContains('Command completed with errors, got failed but valid API response.', $output);
            throw $e;
        }
    }


    public function testGetProductsThrowsExceptionOnChangedApiFormat()
    {
        $this->expectException(CommandErrorException::class);
        $products = array(
            'new' => 'format',
        );
        $body = json_encode($products);

        $response = new Response(200, array(), $body);
        $handler = new MockHandler([$response]);
        $httpClient = new Client(['handler' => $handler]);
        $apiDownloaderService = new ApiDownloaderService('http://example.com', $httpClient);
        $parser = new JsonResponseParser();
        $command = new GetProductsCommand(null, $apiDownloaderService, $parser);
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->add($command);

        $command = $application->find('zhukov:get-products');
        $commandTester = new CommandTester($command);
        try {
            $commandTester->execute(array(
                'command' => $command->getName(),
            ));
        } catch (CommandErrorException $e) {
            $this->assertEquals(CommandErrorException::API_RETURNED_BAD_DATA, $e->getCode());
            throw $e;
        }
    }
}