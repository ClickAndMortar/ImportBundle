<?php

namespace ClickAndMortar\ImportBundle\Reader;

/**
 * Class CsvReader
 *
 * @package ClickAndMortar\ImportBundle\Reader
 */
class CsvReader implements ReaderInterface
{
    /**
     * Read CSV file and return data array
     *
     * @param string $path
     *
     * @return array
     */
    public function read($path, $delimiter = ';')
    {
        $data = array();

        $header = null;
        $handle = fopen($path, 'r');
        while ($row = fgetcsv($handle, null, $delimiter)) {
            if (is_null($header)) {
                $header = $row;
            } else {
                $data[] = array_combine($header, $row);
            }
        }
        fclose($handle);

        return $data;
    }
}
