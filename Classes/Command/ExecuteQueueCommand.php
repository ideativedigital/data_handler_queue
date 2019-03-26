<?php

namespace Ideativedigital\DataHandlerQueue\Command;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Ideativedigital\DataHandlerQueue\Domain\Repository\EntryRepository;
use Ideativedigital\DataHandlerQueue\Utility\DataHandlerUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Command line tool to execute stored TCE commands or data.
 *
 * @package Ideativedigital\DataHandlerQueue\Command
 */
class ExecuteQueueCommand extends Command
{

    const MAXIMUM_QUEUE_ENTRIES = 100;

    /**
     * Configures the command by setting its name, description and options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setDescription('Executes a predefined number of stored TCE information (commands or data). Default is 100.')
                ->setHelp('Use the --limit option to change the number of entries handled in each run of this command.')
                ->addOption(
                        'limit',
                        'i',
                        InputOption::VALUE_OPTIONAL,
                        'Define a maximum number of entries to execute per run.'
                );
    }

    /**
     * Executes the command that runs the selected import.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Make sure the _cli_ user is loaded
        Bootstrap::getInstance()->initializeBackendAuthentication();

        $io = new SymfonyStyle($input, $output);
        $io->title('Executing DataHandler queue entries...');

        // Retrieve and validate the execution limit
        $limit = (int)$input->getOption('limit');
        if ($limit <= 0) {
            $limit = self::MAXIMUM_QUEUE_ENTRIES;
        }
        $entryRepository = GeneralUtility::makeInstance(EntryRepository::class);
        $entries = $entryRepository->findWithLimit($limit);
        $numberOfEntries = count($entries);
        if ($numberOfEntries === 0) {
            $io->success('No entries to execute');
        } else {
            $io->writeln(sprintf('Executing %d entries', $numberOfEntries));

            // Generate the DataHandler structure with the entries that were found
            $dataHandlerUtility = GeneralUtility::makeInstance(DataHandlerUtility::class);
            $structure = $dataHandlerUtility->generateStructure($entries);
            // Mark the entries as executed. In case the script fails by running out of time or memory, we can check what was supposed to be handled
            $dataHandlerUtility->markEntriesAsExecuted($entries);
            // Invoke the DataHandler and execute the data and commands
            $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
            $dataHandler->start($structure['data'], $structure['commands']);
            $dataHandler->process_datamap();
            $dataHandler->process_cmdmap();
            // Delete entries now that they have been executed
            $entryRepository->deleteAllExecuted();

            $io->success('Execution finished successfully');
        }
    }
}