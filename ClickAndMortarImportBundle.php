<?php

namespace ClickAndMortar\ImportBundle;

use ClickAndMortar\ImportBundle\CompilerPass\ReaderDispatcherCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ClickAndMortarImportBundle
 *
 * @package ClickAndMortar\ImportBundle
 */
class ClickAndMortarImportBundle extends Bundle
{
    /**
     * Build.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ReaderDispatcherCompilerPass());
    }
}
