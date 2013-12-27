<?php

namespace Playground\DemoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('playground_demo');

        $rootNode
            ->children()
                ->integerNode('open_hour')
                    ->info('Opening hour of the website, from 0h to 23h')
                    ->example('8')
                    ->isRequired()
                    ->min(0)
                    ->max(23)
                ->end()
                ->integerNode('close_hour')
                    ->info('Closing hour of the website, from 0h to 23h')
                    ->example('18')
                    ->isRequired()
                    ->min(0)
                    ->max(23)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
