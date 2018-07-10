<?php

namespace PeterZhukov\KorablikTestBundle\Tests\Services;

use PeterZhukov\KorablikTestBundle\Exception\ResponseParserException;
use PeterZhukov\KorablikTestBundle\Services\JsonResponseParser;

class JsonResponseParserTest extends \PHPUnit_Framework_TestCase
{

    public function testJsonResponseParserNormalParsesJson()
    {
        $array = array(
            'test' => 'ok',
        );
        $json = json_encode($array);

        $parser = new JsonResponseParser();
        $parserResult = $parser->parse($json);

        $diff = array_diff_assoc($array, $parserResult);

        if (count($diff) == 0) {
            $result = true;
        } else {
            $result = false;
        }
        $this->assertTrue($result);
    }

    public function testJsonResponseParserThrowsExceptionOnWrongJson()
    {
        $this->expectException(ResponseParserException::class);
        $json = '{error';
        $parser = new JsonResponseParser();
        try {
            $parser->parse($json);
        } catch (ResponseParserException $e) {
            $this->assertEquals(ResponseParserException::GENERAL_PARSER_ERROR, $e->getCode());
            throw $e;
        }
    }
}