<?php

namespace Acme\DemoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    static private $categories = array('programming', 'jobs', 'internet', 'music');

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('acme_demo');

        $rootNode
            ->children()
                ->integerNode('max_per_page')
                    ->isRequired()
                    ->min(1)
                    ->max(50)
                ->end()
                ->enumNode('active_categories')
                    ->isRequired()
                    ->values(self::$categories)
                ->end()
                ->scalarNode('main_category')
                    ->isRequired()
                    ->validate()
                        ->ifTrue(function ($category) { return !in_array($category, self::$categories); })
                        ->thenInvalid(sprintf('Invalid category provided. Must be one of %s', implode(', ', self::$categories)))
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
