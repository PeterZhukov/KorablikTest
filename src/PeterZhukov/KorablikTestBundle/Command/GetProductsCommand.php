<?php

namespace PeterZhukov\KorablikTestBundle\Command;

use PeterZhukov\KorablikTestBundle\Exception\CommandErrorException;
use PeterZhukov\KorablikTestBundle\Services\ApiDownloaderInterface;
use PeterZhukov\KorablikTestBundle\Services\ResponseParserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * <help>
 *      Вызови эту команду, если необходимо получить данные по товарам от поставщика
 * </help>
 */
class GetProductsCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'zhukov:get-products';

    protected $apiDownloader;
    protected $responseParser;

    /**
     * <help>
     *      $apiDownloader - класс, делающий get запрос к поставщику
     *      $responseParser - класс, парсящий json или xml от поставщика (разные классы), и возвращающий PHP массив
     * </help>
     */
    public function __construct($name = null, ApiDownloaderInterface $apiDownloader, ResponseParserInterface $responseParser)
    {
        $this->apiDownloader = $apiDownloader;
        $this->responseParser = $responseParser;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Get supplier products');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stringData = $this->apiDownloader->download('/products');

        //Для тестирования различных ситуаций (xml, API вернула ошибку)
        #$stringData = $this->apiDownloader->download('/products_xml');
        #$stringData = $this->apiDownloader->download('/products_error');
        #$stringData = $this->apiDownloader->download('/products_xml_error');

        $productsData = $this->responseParser->parse($stringData);

        if (isset($productsData) && isset($productsData['success']) && $productsData['success'] === true) {
            dump($productsData);
            $output->writeln("Command completed successfully.");
        } elseif (isset($productsData) && isset($productsData['success']) && $productsData['success'] === false) {
            // Здесь я бы включил логирование, и затем уже просматривал логи на предмет неработы API поставщика
            // и рассылал всем сообщения, что импорт не прошел.

            dump($productsData);
            $output->writeln("Command completed with errors, got failed but valid API response.");
            $exception = new CommandErrorException("Api returned bad data", CommandErrorException::API_RESPONSE_RETURNED_FALUIRE);
            $exception->setApiData($productsData);
            throw $exception;
        } else {
            // Аналогично, можно логировать.

            $exception = new CommandErrorException("Api returned bad data", CommandErrorException::API_RETURNED_BAD_DATA);
            $exception->setApiData($productsData);
            throw $exception;
        }

    }

}
