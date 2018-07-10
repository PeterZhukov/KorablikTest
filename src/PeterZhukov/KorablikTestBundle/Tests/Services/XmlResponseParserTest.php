<?php

namespace PeterZhukov\KorablikTestBundle\Tests\Services;

use PeterZhukov\KorablikTestBundle\Exception\ResponseParserException;
use PeterZhukov\KorablikTestBundle\Exception\XmlResponseParserException;
use PeterZhukov\KorablikTestBundle\Services\JsonResponseParser;
use PeterZhukov\KorablikTestBundle\Services\XmlResponseParser;

class XmlResponseParserTest extends \PHPUnit_Framework_TestCase
{

    public function testXmlResponseParserNormalParsesXml()
    {
        $array = array(
            'test' => 'ok',
        );
        $xml = '<?xml version="1.0"?><root><test>ok</test></root>';

        $parser = new XmlResponseParser();
        $parserResult = $parser->parse($xml);

        $diff = array_diff_assoc($array, $parserResult);

        if (count($diff) == 0) {
            $result = true;
        } else {
            $result = false;
        }
        $this->assertTrue($result);
    }

    public function testXmlResponseParserThrowsExceptionOnWrongXml()
    {
        $this->expectException(XmlResponseParserException::class);
        $xml = '<roo';
        $parser = new XmlResponseParser();
        try {
            $parser->parse($xml);
        } catch (XmlResponseParserException $e) {
            $this->assertEquals(XmlResponseParserException::UNABLE_TO_PARSE_XML, $e->getCode());
            throw $e;
        }
    }
}