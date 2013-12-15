<?php

namespace Acme\DemoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class DatabaseConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('acme_blog');

        $rootNode
            ->children()
                ->integerNode('max_per_page')
                    ->isRequired()
                    ->min(1)
                    ->max(50)
                ->end()
                ->arrayNode('active_categories')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->enumNode('main_category')
                    ->isRequired()
                    ->values(array('programming', 'jobs', 'internet', 'music'))
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
