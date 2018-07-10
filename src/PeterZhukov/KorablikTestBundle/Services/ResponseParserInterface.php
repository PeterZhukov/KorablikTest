<?php
namespace PeterZhukov\KorablikTestBundle\Services;

/**
 * <help>
 *      Интерфейс, использующийся для классов пробразующих JSON и XML в массив. Используй, если добавляешь новый
 *      преобразователь (CSV -> массив)
 * </help>
 */
interface ResponseParserInterface{
    public function parse($string);
}