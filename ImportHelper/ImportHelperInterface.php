<?php

namespace ClickAndMortar\ImportBundle\ImportHelper;

/**
 * Interface ImportHelperInterface
 *
 */
interface ImportHelperInterface
{
    /**
     * Complete $entity with $data from import
     *
     * @param mixed $entity
     * @param array $data
     * @param array $errors
     *
     * @return void
     */
    public function completeData(&$entity, array $data, array &$errors);
}
