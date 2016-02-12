<?php

namespace ClickAndMortar\ImportBundle\Reader;

/**
 * Interface ReaderInterface
 *
 * @package ClickAndMortar\ImportBundle\Reader
 */
interface ReaderInterface
{
    /**
     * Read a file from $path with $delimiter column and return data array
     *
     * @param string $path
     * @param string $delimiter
     *
     * @return array mixed
     */
    public function read($path, $delimiter = ';');
}
