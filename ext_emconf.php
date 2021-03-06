<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "rkw_order"
 *
 * Auto generated by Extension Builder 2016-05-04
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'RKW Order',
	'description' => '',
	'category' => 'plugin',
    'author' => 'Maximilian Fäßler, Steffen Kroggel',
    'author_email' => 'faesslerweb@web.de, developer@steffenkroggel.de',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '7.6.12',
	'constraints' => array(
		'depends' => array(
            'extbase' => '6.2.0-7.6.99',
            'fluid' => '6.2.0-7.6.99',
            'typo3' => '6.2.0-7.6.99',
            'rkw_basics' => '7.6.10-7.6.99',
            'rkw_mailer' => '7.6.13-7.6.99',
            'rkw_registration' => '7.6.10-7.6.99'
		),
		'conflicts' => array(
            'rkw_soap' => '7.6.5-8.7.99',
		),
		'suggests' => array(
		),
	),
);