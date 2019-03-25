<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "svconnector".
 *
 * Auto generated 05-04-2017 17:36
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
        'title' => 'DataHandler Queue',
        'description' => 'Provides an API for registering DataHandler data and commands and replay them asynchronously at a later point in time.',
        'category' => 'backend',
        'author' => 'Francois Suter (Idéative)',
        'author_email' => 'francois.suter@ideative.ch',
        'state' => 'alpha',
        'uploadfolder' => 0,
        'createDirs' => '',
        'clearCacheOnLoad' => 1,
        'author_company' => '',
        'version' => '0.0.1',
        'constraints' =>
                [
                        'depends' =>
                                [
                                        'typo3' => '8.7.0-9.99.99',
                                ],
                        'conflicts' =>
                                [
                                ],
                        'suggests' =>
                                [
                                ],
                ],
];

