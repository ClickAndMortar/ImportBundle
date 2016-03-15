<?php

namespace ClickAndMortar\ImportBundle\Reader\Readers;

use ClickAndMortar\ImportBundle\Reader\AbstractReader;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CsvReader
 *
 * @package ClickAndMortar\ImportBundle\Reader\Readers
 */
class CsvReader extends AbstractReader
{
    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            array(
                'delimiter' => ';',
            )
        );
    }

    /**
     * Read CSV file and return data array
     *
     * @param string $path
     *
     * @return array
     */
    public function read($path)
    {
        $data = array();

        $header = null;
        $handle = fopen($path, 'r');
        while ($row = fgetcsv($handle, null, $this->options['delimiter'])) {
            if (is_null($header)) {
                $header = $row;
            } else {
                $data[] = array_combine($header, $row);
            }
        }
        fclose($handle);

        return $data;
    }

    /**
     * Support only csv type
     *
     * @param string $type
     *
     * @return bool
     */
    public function support($type)
    {
        return $type == 'csv';
    }
}
