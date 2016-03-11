<?php

namespace ClickAndMortar\ImportBundle\Reader;

/**
 * Class ReaderDispatcher
 *
 * @package ClickAndMortar\ImportBundle\Reader
 */
class ReaderDispatcher
{
    /**
     * All readers available.
     *
     * @var AbstractReader[]
     */
    private $readers;

    /**
     * ReaderDispatcher constructor.
     */
    public function __construct()
    {
        $this->readers = array();
    }

    /**
     * Add a reader to list.
     *
     * @param AbstractReader $reader
     */
    public function addReader(AbstractReader $reader)
    {
        $this->readers[] = $reader;
    }

    /**
     * Get readers.
     *
     * @return AbstractReader[]
     */
    public function getReaders()
    {
        return $this->readers;
    }

    /**
     * Get reader for $type.
     *
     * @param string $type
     *
     * @return AbstractReader
     */
    public function getReaderByType($type)
    {
        foreach ($this->getReaders() as $reader) {
            if ($reader->support($type)) {
                return $reader;
            }
        }

        return null;
    }

    /**
     * Has reader by $type.
     *
     * @param string $type
     *
     * @return bool
     */
    public function hasReaderByType($type)
    {
        return !is_null($this->getReaderByType($type));
    }
}
