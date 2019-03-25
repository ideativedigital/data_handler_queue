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