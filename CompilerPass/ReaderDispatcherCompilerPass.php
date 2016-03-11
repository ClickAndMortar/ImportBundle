<?php

namespace ClickAndMortar\ImportBundle\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ReaderDispatcherCompilerPass
 *
 * @package ClickAndMortar\ImportBundle\CompilerPass
 */
class ReaderDispatcherCompilerPass implements CompilerPassInterface
{
    /**
     * Reader dispatcher service id.
     *
     * @var string
     */
    const READER_DISPATCHER_SERVICE_ID = 'clickandmortar.import_bundle.reader_dispatcher';

    /**
     * Readers services tag.
     *
     * @var string
     */
    const READERS_SERVICES_TAG = 'clickandmortar.import.reader';

    /**
     * Process.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::READER_DISPATCHER_SERVICE_ID)) {
            return;
        }

        $definition     = $container->getDefinition(self::READER_DISPATCHER_SERVICE_ID);
        $taggedServices = $container->findTaggedServiceIds(self::READERS_SERVICES_TAG);
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addReader',
                array(new Reference($id))
            );
        }
    }
}
