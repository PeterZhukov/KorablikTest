<?php

namespace PeterZhukov\KorablikTestBundle\Tests\Controler;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testProductsUrlReturnsNormalData()
    {
        $kernel = static::bootKernel();
        $serverName = $kernel->getContainer()->getParameter('tests_server_name');
        if (empty($serverName)) {
            $serverName = 'localhost';
        }
        $client = static::createClient(array(), array('HTTP_HOST' => $serverName));
        $resposne = $client->request('GET', '/test_zhukov/products');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('products', $client->getResponse()->getContent());
    }

    public function testProductsXmlUrlReturnsNormalData()
    {
        $kernel = static::bootKernel();
        $serverName = $kernel->getContainer()->getParameter('tests_server_name');
        if (empty($serverName)) {
            $serverName = 'localhost';
        }
        $client = static::createClient(array(), array('HTTP_HOST' => $serverName));
        $resposne = $client->request('GET', '/test_zhukov/products_xml');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('products', $client->getResponse()->getContent());
    }

    public function testProductsErrorUrlReturnsNormalData()
    {
        $kernel = static::bootKernel();
        $serverName = $kernel->getContainer()->getParameter('tests_server_name');
        if (empty($serverName)) {
            $serverName = 'localhost';
        }
        $client = static::createClient(array(), array('HTTP_HOST' => $serverName));
        $resposne = $client->request('GET', '/test_zhukov/products_error');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('success', $client->getResponse()->getContent());
    }

    public function testProductsXmlErrorUrlReturnsNormalData()
    {
        $kernel = static::bootKernel();
        $serverName = $kernel->getContainer()->getParameter('tests_server_name');
        if (empty($serverName)) {
            $serverName = 'localhost';
        }
        $client = static::createClient(array(), array('HTTP_HOST' => $serverName));
        $resposne = $client->request('GET', '/test_zhukov/products_xml_error');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('success', $client->getResponse()->getContent());
    }
}