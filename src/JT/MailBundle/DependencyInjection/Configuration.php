<?php

namespace JT\MailBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jt_mail');

        $rootNode
            ->children()
                ->arrayNode('templates')->isRequired()->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('header')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('html')->defaultNull()->end()
                                ->scalarNode('text')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('footer')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('html')->defaultNull()->end()
                                ->scalarNode('text')->defaultNull()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
