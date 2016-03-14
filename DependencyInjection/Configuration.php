<?php

namespace ClickAndMortar\ImportBundle\DependencyInjection;

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
        $rootNode    = $treeBuilder->root('click_and_mortar_import');
        $rootNode
            ->children()
                ->arrayNode('entities')
                    ->useAttributeAsKey('name')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('model')
                                ->info('Complete entity class name (eg. Acme\DemoBundle\Entity\Customer)')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('repository')
                                ->info('Repository for current entity (eg. AcmeDemoBundle:Customer)')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('unique_key')
                                ->info('Field to check entity in database and update only')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('complete_mapping_method')
                                ->info('Method call on entity to complete classic fields mapping')
                            ->end()
                            ->booleanNode('only_update')
                                ->info('Use only unique_key to update entities')
                                ->defaultFalse()
                            ->end()
                            ->arrayNode('mappings')
                                ->requiresAtLeastOneElement()
                                ->prototype('scalar')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
