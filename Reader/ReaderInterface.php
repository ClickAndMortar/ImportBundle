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
     * Read a file from $path and return data array
     *
     * @param string $path
     *
     * @return array mixed
     */
    public function read($path);
}
