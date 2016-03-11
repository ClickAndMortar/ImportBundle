<?php

namespace ClickAndMortar\ImportBundle\Reader;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ReaderInterface
 *
 * @package ClickAndMortar\ImportBundle\Reader
 */
abstract class AbstractReader implements ReaderInterface
{
    /**
     * Reader options
     *
     * @var array
     */
    protected $options = array();

    /**
     * AbstractReader constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array()
        );
    }
}
