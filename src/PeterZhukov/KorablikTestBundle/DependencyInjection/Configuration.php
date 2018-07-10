<?php

namespace PeterZhukov\KorablikTestBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;



class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('peter_zhukov_korablik_test');
        $rootNode
            ->children()
                ->scalarNode('api_base_url')
                    ->defaultValue('')
                    ->isRequired()
                    ->validate()
                        ->always(function($value) {
                            $valid = filter_var($value, FILTER_VALIDATE_URL);
                            if (!$valid){
                                $msg = sprintf('The child node "%s" at path "%s" must be valid url in following format: scheme://hos.t/path. %s given.', "api_url", "peter_zhukov_korablik_test", $value);
                                $ex = new InvalidConfigurationException($msg);
                                $ex->setPath("peter_zhukov_korablik_test");
                                throw $ex;
                            }

                            $url = parse_url($value);
                            $bUrlValid = true;
                            if (empty($url)){
                                $bUrlValid = false;
                            }
                            if (empty($url['scheme'])){
                                $bUrlValid = false;
                            }
                            if (empty($url['host'])){
                                $bUrlValid = false;
                            }
                            if (!$bUrlValid){
                                $msg = sprintf('The child node "%s" at path "%s" must be valid url in following format: scheme://hos.t/path. %s given.', "api_url", "peter_zhukov_korablik_test", $value);
                                $ex = new InvalidConfigurationException($msg);
                                $ex->setPath("peter_zhukov_korablik_test");
                                throw $ex;
                            }
                            $format = '';
                            if (!empty($url['scheme'])){
                                $format .= 'scheme://';
                            }
                            if (!empty($url['host'])){
                                $format .= 'hos.t';
                            }
                            if (!empty($url['path'])){
                                $format .= '/path';
                            }
                            if (!empty($url['query'])){
                                $format .= '?query';
                            }
                            if (strpos($value, '#') !== false){
                                $format .= '#hash';
                            }
                            if ($format != 'scheme://hos.t/path'){
                                $msg = sprintf('The child node "%s" at path "%s" must be valid url in following format only: scheme://hos.t/path. %s given.', "api_url", "peter_zhukov_korablik_test", $format);
                                $ex = new InvalidConfigurationException($msg);
                                $ex->setPath("peter_zhukov_korablik_test");
                                throw $ex;
                            }
                            return $value;
                        })
                    ->end()
                ->end()
                ->enumNode('api_reponse_format')
                    ->values(array('json', 'xml'))
                    ->defaultValue('')
                    ->isRequired()
                ->end()
        ;
        return $treeBuilder;
    }
}
