<?php
namespace Ideativedigital\DataHandlerQueue\Tests\Unit\Utility;

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

use Ideativedigital\DataHandlerQueue\Utility\DataHandlerUtility;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test suite for the DataHandlerUtility class.
 *
 * @package Ideativedigital\DataHandlerQueue\Tests\Unit\Utility
 */
class DataHandlerUtilityTest extends UnitTestCase
{
    /**
     * @var DataHandlerUtility
     */
    protected $subject;

    public function setUp()
    {
        parent::setUp();
        $this->subject = GeneralUtility::makeInstance(DataHandlerUtility::class);
    }

    /**
     * @return array
     */
    public function entriesProviders(): array
    {
        return [
                'empty list' => [
                        'entries' => [],
                        'result' => [
                                'data' => [],
                                'commands' => []
                        ]
                ],
                'single data entry' => [
                        'entries' => [
                                0 => [
                                        'uid' => 1,
                                        'tablename' => 'pages',
                                        'fieldname' => 'title',
                                        'record_uid' => '42',
                                        'value' => 'Zaphod Beeblebrox'
                                ]
                        ],
                        'result' => [
                                'data' => [
                                        'pages' => [
                                                '42' => [
                                                        'title' => 'Zaphod Beeblebrox'
                                                ]
                                        ]
                                ],
                                'commands' => []
                        ]
                ],
                'single command entry' => [
                        'entries' => [
                                0 => [
                                        'uid' => 1,
                                        'tablename' => 'pages',
                                        'command' => 'delete',
                                        'record_uid' => '42',
                                        'value' => 1
                                ]
                        ],
                        'result' => [
                                'data' => [],
                                'commands' => [
                                        'pages' => [
                                                '42' => [
                                                        'delete' => 1
                                                ]
                                        ]
                                ]
                        ]
                ],
                'multiple data entries for the same record' => [
                        'entries' => [
                                0 => [
                                        'uid' => 1,
                                        'tablename' => 'pages',
                                        'fieldname' => 'title',
                                        'record_uid' => '42',
                                        'value' => 'Zaphod Beeblebrox'
                                ],
                                1 => [
                                        'uid' => 2,
                                        'tablename' => 'pages',
                                        'fieldname' => 'nav_title',
                                        'record_uid' => '42',
                                        'value' => 'Heart of gold'
                                ]
                        ],
                        'result' => [
                                'data' => [
                                        'pages' => [
                                                '42' => [
                                                        'title' => 'Zaphod Beeblebrox',
                                                        'nav_title' => 'Heart of gold'
                                                ]
                                        ]
                                ],
                                'commands' => []
                        ]
                ],
        ];
    }

    /**
     * @test
     * @dataProvider entriesProviders
     * @param array $entries
     * @param array $result
     */
    public function generateStructureReturnsExpectedDataHandlerStructure(array $entries, array $result) {
        self::assertSame(
                $result,
                $this->subject->generateStructure($entries)
        );
    }
}