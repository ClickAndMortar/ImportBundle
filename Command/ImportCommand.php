<?php

namespace ClickAndMortar\ImportBundle\Command;

use ClickAndMortar\ImportBundle\ImportHelper\ImportHelperInterface;
use ClickAndMortar\ImportBundle\Reader\AbstractReader;
use ClickAndMortar\ImportBundle\Reader\Readers\CsvReader;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCommand
 *
 * @package ClickAndMortar\ImportBundle\Command
 */
class ImportCommand extends ContainerAwareCommand
{
    /**
     * Persist 30 per 30 entities in same time
     *
     * @var integer
     */
    const CHUNK_SIZE = 30;

    /**
     * @var AbstractReader
     */
    protected $reader = null;

    /**
     * Import helper
     *
     * @var ImportHelperInterface
     */
    protected $importHelper = null;

    public function __construct(CsvReader $reader)
    {
      $this->reader = $reader;
      parent::__construct();
    }
    /**
     * Configure command
     */
    protected function configure()
    {
        $this->setName('app:import')
             ->setDescription('Import file to create entities')
             ->addArgument('path', InputArgument::REQUIRED, 'File path (eg. "/home/user/my-data.csv")')
             ->addArgument(
                 'entity',
                 InputArgument::REQUIRED,
                 'Entity name used in your configuration file under click_and_mortar_import.entities node (eg. "customer")'
             )
             ->addOption('delete-after-import', 'd', InputOption::VALUE_NONE, 'To delete file after import');
    }

    /**
     * Check arguments
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        // Check path argument
        $container = $this->getContainer();
        $path      = $input->getArgument('path');
        if (file_exists($path) && is_readable($path)) {
            // Get reader by extension
            $fileExtension          = pathinfo($path, PATHINFO_EXTENSION);
            $fileExtensionFormatted = strtolower($fileExtension);

        } else {
            $errorMessage = sprintf(
                'File %s does not exist',
                $path
            );

            throw new InvalidArgumentException($errorMessage);
        }

        // Check entity argument
        $entity   = $input->getArgument('entity');
        $entities = $container->getParameter('entities');
        if (!array_key_exists($entity, $entities)) {
            $errorMessage = sprintf(
                '%s entity short name does not exist in your configuration file',
                $entity
            );

            throw new InvalidArgumentException($errorMessage);
        }

        // Check for import helper if necessary
        $importHelperService = isset($entities[$entity]['import_helper_service']) ? $entities[$entity]['import_helper_service'] : null;
        if (!is_null($importHelperService)) {
            if ($container->has($importHelperService)) {
                $this->importHelper = $container->get($importHelperService);
            } else {
                $errorMessage = sprintf(
                    '%s import helper does not exist',
                    $entity
                );

                throw new InvalidArgumentException($errorMessage);
            }
        }
    }

    /**
     * Execute import
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container           = $this->getContainer();
        $entities            = $container->getParameter('entities');
        $path                = $input->getArgument('path');
        $entity              = $input->getArgument('entity');
        $entityConfiguration = $entities[$entity];
        $errors              = array();

        /** @var EntityManager $entityManager */
        $entityManager   = $container->get('doctrine')->getManager();
        $repository      = $entityManager->getRepository($entityConfiguration['repository']);
        $uniqueKey       = isset($entityConfiguration['unique_key']) ? $entityConfiguration['unique_key']: null;
        $mapping         = $entityConfiguration['mappings'];
        $entityClassname = $entityConfiguration['model'];
        $onlyUpdate      = $entityConfiguration['only_update'];

        // Read file
        $rows     = $this->reader->read($path);
        $size     = count($rows);
        $index    = 1;
        $progress = new ProgressBar($output, $size);
        $progress->start();

        // Create each entity
        foreach ($rows as $row) {
            if ($uniqueKey) {
                $criteria = array(
                    $uniqueKey => $row[$mapping[$uniqueKey]],
                );
                $entity   = $repository->findOneBy($criteria);
            } else {
                $entity = null;
            }
            if (is_null($entity) && $onlyUpdate === false) {
                $entity = new $entityClassname();
            }

            if (!is_null($entity)) {
                // Set fields
                foreach ($mapping as $entityPropertyKey => $filePropertyKey) {
                    $setter = sprintf(
                        'set%s',
                        ucfirst($entityPropertyKey)
                    );
                    $entity->{$setter}($row[$filePropertyKey]);
                }

                // Complete data if necessary
                if (!is_null($this->importHelper)) {
                    $this->importHelper->completeData($entity, $row, $errors);
                }
                $entityManager->persist($entity);
            }

            // Persist if necessary
            if (($index % self::CHUNK_SIZE) === 0) {
                $entityManager->flush();
                $entityManager->clear();
                $progress->advance(self::CHUNK_SIZE);
            }
            $index++;
        }
        $entityManager->flush();
        $entityManager->clear();
        $progress->finish();

        if ($input->getOption('delete-after-import') == true) {
            unlink($path);
        }

        // Print errors if necessary
        foreach ($errors as $error) {
            $output->writeln(sprintf('<error>%s</error>', $error));
        }
    }
}
