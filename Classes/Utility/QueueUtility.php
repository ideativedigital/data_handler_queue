<?php
namespace Ideativedigital\DataHandlerQueue\Utility;

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
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * API for storing DataHandler data or commands to the database.
 *
 * @package Ideativedigital\DataHandlerQueue\Utility
 */
class QueueUtility implements SingletonInterface
{
    /**
     * @var EntryRepository
     */
    protected $entryRepository;

    public function __construct()
    {
        $this->entryRepository = GeneralUtility::makeInstance(EntryRepository::class);
    }

    /**
     * Handles an entry for storage.
     *
     * Currently this is just a wrapper around the repository, but it was done so to allow for processing or
     * whatever else before the actual storage happens without breaking the API.
     *
     * @param string $table Name of the affected table
     * @param mixed $uid Id of the affected record (can be "NEW***" for a new record)
     * @param mixed $value Value to store for data, or related to the command
     * @param string $field Name of the affected field (in case of data)
     * @param string $command Command to execute (empty for data)
     * @return void (TODO: we could send some feedback about success or failure)
     */
    public function store($table, $uid, $value, $field = '', $command = '')
    {
        $this->entryRepository->add($table, $uid, $value, $field, $command);
    }
}