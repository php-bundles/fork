<?php

namespace SymfonyBundles\ForkBundle\DependencyInjection;

use SymfonyBundles\ForkBundle\Service;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $builder->root('sb_fork')
            ->children()
                ->scalarNode('service_name')->defaultValue('fork')->cannotBeEmpty()->end()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('fork')->defaultValue(Service\Fork::class)->end()
                        ->scalarNode('process')->defaultValue(Service\Process::class)->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }

}
