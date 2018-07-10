<?php

namespace PeterZhukov\KorablikTestBundle\Exception;

class XmlResponseParserException extends ResponseParserException
{
    const UNABLE_TO_PARSE_XML = 1;
    const UNABLE_TO_CONVERT_XML_TO_ARRAY = 2;
}