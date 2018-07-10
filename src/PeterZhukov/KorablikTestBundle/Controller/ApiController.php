<?php

namespace PeterZhukov\KorablikTestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * <help>
 *      Класс, реализующий пример API товаров поставщика
 * </help>
 */
class ApiController extends Controller
{
    /**
    Есть некоторый поставщик товаров ООО КРУПНЫЙ ПОСТАВЩИК, который предоставляет своё API для интеграции с интернет-магазинами.
    Пока у этого поставщика готов только один метод
    [GET /products]
    , который возвращает список товаров в следующем формате:
    {
    "data": {
        "products": [
            {
                "vendor_code": "BDK123L",
                "name": "Товар 1",
                "price": 100,
                "features": {
                    "some_feature1": 100,
                    "some_feature2": 'some text'
                }
            },
            ...
        ]
    },
        "success": true
    }

    В случае ошибки на стороне удаленного API бует возвращено сообщение об ошибке:
    {
        "data": {
            "message": "string error message",
            "code": "string error code"
        },
        "success": false
    }

    Необходимо разработать модуль (в виде бандла Symfony 3, библиотека для Silex и т.п.), которая будет работать с удаленным API этого поставщика.
    При разработке необходио учесть, что, в ближайшем будущем, поставщик сменит формат ответа с JSON на XML. Код следует покрывать unit-тестами и
    готовое решение, желательно, выложить на какую-нибудь популярную площадку с репозиториями (типа Github)
     */

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

    /**
     * <help>
     *      Экшн возвращает правильные товары от поставщика, в формате JSON
     * </help>
     */
    public function productsAction()
    {
        return $this->json($this->products);
    }

    /**
     * <help>
     *      Экшн возвращает правильные товары от поставщика, в формате XML
     * </help>
     */
    public function productsXmlAction(){
        $products = $this->products;
        $xml = new \DOMDocument('1.0', 'utf-8');
        $root = new \DOMElement('root');
        $xml->appendChild($root);
        $this->convertToXml($products, $root);
        $xml->formatOutput = true;
        $response = new Response($xml->saveXML());
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;

    }

    /**
     * <help>
     *      Экшн возвращает ошибку от якобы API поставщика, в формате JSON
     * </help>
     */
    public function productsErrorAction()
    {
        return $this->json($this->productsError);
    }


    /**
     * <help>
     *      Экшн возвращает ошибку от якобы API поставщика, в формате XML
     * </help>
     */
    public function productsXmlErrorAction(){
        $products = $this->productsError;
        $xml = new \DOMDocument('1.0', 'utf-8');
        $root = new \DOMElement('root');
        $xml->appendChild($root);
        $this->convertToXml($products, $root);
        $xml->formatOutput = true;
        $response = new Response($xml->saveXML());
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }


    /**
     * <help>
     *      Вспомогательная функция, конвертирует массив (даже вложенный) в XML
     * </help>
     */
    protected function convertToXml($array, \DOMElement $xml){
        foreach($array as $key => $val){
            if (is_array($val)) {
                $childXml = new \DOMElement($key);
                $xml->appendChild($childXml);
                $this->convertToXml($val, $childXml);
            } elseif ($val === true) {
                $childXml = new \DOMElement($key, 'true');
                $xml->appendChild($childXml);
            } elseif ($val === false){
                $childXml = new \DOMElement($key, 'false');
                $xml->appendChild($childXml);
            } else {
                $childXml = new \DOMElement($key, $val);
                $xml->appendChild($childXml);
            }
        }
    }
}
