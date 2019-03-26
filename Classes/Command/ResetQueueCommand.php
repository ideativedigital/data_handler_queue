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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Command line tool to execute stored TCE commands or data.
 *
 * @package Ideativedigital\DataHandlerQueue\Command
 */
class ResetQueueCommand extends Command
{

    /**
     * Configures the command by setting its name, description and options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setDescription('Resets the "execute" flag for all marked entries.');
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
        $io->title('Resetting execute flag for queue entries...');

        $entryRepository = GeneralUtility::makeInstance(EntryRepository::class);
        $entryRepository->resetExecuted();

        $io->success('Finished reset');
    }
}