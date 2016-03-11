<?php

namespace ClickAndMortar\ImportBundle\Reader;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CsvReader
 *
 * @package ClickAndMortar\ImportBundle\Reader
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
}
