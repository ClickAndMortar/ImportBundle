<?php

namespace ClickAndMortar\ImportBundle\Service;

/**
 * Interface ImportHelperInterface
 *
 * @package ClickAndMortar\ImportBundle\Service
 */
interface ImportHelperInterface
{
    /**
     * Complete $entity with $data from import
     *
     * @param mixed $entity
     * @param array $data
     *
     * @return void
     */
    public function completeData(&$entity, array $data);
}
