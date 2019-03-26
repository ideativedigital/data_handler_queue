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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Tools to convert a list of stored entries into the DataHandler-compatible structure.
 *
 * @package Ideativedigital\DataHandlerQueue\Utility
 */
class DataHandlerUtility
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
     * Assembles a TCE structure for data and commands based on a list of stored entries.
     *
     * @param array $entries List of stored data and commands
     * @return array
     */
    public function generateStructure(array $entries): array
    {
        $structure = [
                'data' => [],
                'commands' => []
        ];
        foreach ($entries as $entry) {
            // If the command field is empty, it's a data entry
            if (empty($entry['command'])) {
                if (!array_key_exists($entry['tablename'], $structure['data'])) {
                    $structure['data'][$entry['tablename']] = [];
                }
                $structure['data'][$entry['tablename']][$entry['record_uid']] = [
                        $entry['fieldname'] => $entry['value']
                ];
            // It's a command entry
            } else {
                if (!array_key_exists($entry['tablename'], $structure['data'])) {
                    $structure['commands'][$entry['tablename']] = [];
                }
                $structure['commands'][$entry['tablename']][$entry['record_uid']] = [
                        $entry['command'] => $entry['value']
                ];
            }
            // Mark handled entries as executed
            $this->entryRepository->setExecuted($entry['uid']);
        }
        return $structure;
    }
}