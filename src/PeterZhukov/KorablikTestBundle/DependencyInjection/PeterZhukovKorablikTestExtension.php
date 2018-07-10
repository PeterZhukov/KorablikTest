<?php

namespace PeterZhukov\KorablikTestBundle\DependencyInjection;

use PeterZhukov\KorablikTestBundle\Services\ApiDownloaderService;
use PeterZhukov\KorablikTestBundle\Services\ResponseParserInterface;
use PeterZhukov\KorablikTestBundle\Services\XmlResponseParser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;


class PeterZhukovKorablikTestExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $definition = $container->getDefinition(ApiDownloaderService::class);
        $definition->replaceArgument(0, $config['api_base_url']);

        if ($config['api_reponse_format'] == 'xml') {
            $container->setAlias('PeterZhukov\KorablikTestBundle\Services\ResponseParserInterface', XmlResponseParser::class);
        }

    }
}
