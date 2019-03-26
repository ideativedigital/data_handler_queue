<?php

namespace Ideativedigital\DataHandlerQueue\Domain\Repository;

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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for CRUD operations with queue entries.
 *
 * @package Ideativedigital\DataHandlerQueue\Command
 */
class EntryRepository
{
    /**
     * Fetches a number of entries from the queue.
     *
     * NOTE: entries are ordered by uid to execute them in the order in which they were entered
     * (first in, first out).
     *
     * @param int $limit Maximum number of records to return
     * @return array
     */
    public function findWithLimit(int $limit): array
    {
        $queryBuilder = $this->getQueryBuilder();
        $result = $queryBuilder->select('*')
                ->from('tx_datahandlerqueue_domain_model_entry')
                ->orderBy('uid')
                ->setMaxResults($limit)
                ->execute();
        return $result->fetchAll();
    }

    /**
     * Adds an entry to the DB.
     *
     * @param string $table Name of the affected table
     * @param mixed $uid Id of the affected record (can be "NEW***" for a new record)
     * @param mixed $value Value to store for data, or related to the command
     * @param string $field Name of the affected field (in case of data)
     * @param string $command Command to execute (empty for data)
     * @return void (TODO: we could send some feedback about success or failure)
     */
    public function add($table, $uid, $value, $field, $command)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert('tx_datahandlerqueue_domain_model_entry')
                ->values([
                        'tablename' => $table,
                        'fieldname' => $field,
                        'record_uid' => $uid,
                        'command' => $command,
                        'value' => $value
                ])
                ->execute();
    }

    /**
     * Sets the executed flag of the given record to true.
     *
     * @param int $uid Id of the record
     * @return void
     */
    public function setExecuted(int $uid)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update('tx_datahandlerqueue_domain_model_entry')
                ->set('executed', 1)
                ->execute();
    }

    /**
     * Resets the executed flag (to 0) for all records where it is set.
     *
     * @return void
     */
    public function resetExecuted()
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update('tx_datahandlerqueue_domain_model_entry')
                ->set('executed', 0)
                ->where(
                        $queryBuilder->expr()->eq('executed', 1)
                )
                ->execute();
    }

    /**
     * Deletes all records that have been marked as executed.
     *
     * @return void
     */
    public function deleteAllExecuted()
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete('tx_datahandlerqueue_domain_model_entry')
                ->where(
                        $queryBuilder->expr()->eq('executed', 1)
                )
                ->execute();
    }

    /**
     * Returns an instance of the QueryBuilder for the entries table.
     *
     * @return QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_datahandlerqueue_domain_model_entry');
    }
}