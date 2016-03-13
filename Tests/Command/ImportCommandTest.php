<?php

namespace ClickAndMortar\ImportBundle\Tests\Command;

use ClickAndMortar\ImportBundle\Command\ImportCommand;
use ClickAndMortar\ImportBundle\Reader\ReaderDispatcher;
use ClickAndMortar\ImportBundle\Reader\Readers\CsvReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\Container;

class ResetCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Command
     */
    private $command;

    private $file;

    /**
     * @var ReaderDispatcher
     */
    private $dispatcher;

    public function setup()
    {
        $this->file = __DIR__.'/../fixtures/import_file.csv';
        $this->dispatcher = new ReaderDispatcher();
        $this->dispatcher->addReader(new CsvReader());

        $container = new Container();
        $container->set('clickandmortar.import_bundle.reader_dispatcher', $this->dispatcher);
        $this->command = new ImportCommand();
        $this->command->setContainer($container);
    }

    public function testImportCsv()
    {
        $this->command->run(new ArrayInput(['path' => $this->file]), new NullOutput());

        $this->setExpectedException('InvalidArgumentException', 'File  does not exist');
        $this->command->run(new ArrayInput([]), new NullOutput());
    }
}
