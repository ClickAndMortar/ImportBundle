<?php

namespace ClickAndMortar\ImportBundle;

use ClickAndMortar\ImportBundle\DependencyInjection\ClickAndMortarImportExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ClickAndMortarImportBundle
 *
 * @package ClickAndMortar\ImportBundle
 */
class ClickAndMortarImportBundle extends Bundle
{

    public function getContainerExtension()
    {
        return new ClickAndMortarImportExtension();
    }

    /**
     * Build.
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        //$container->registerExtension(new ClickAndMortarImportExtension());
        parent::build($container);
    }
}
