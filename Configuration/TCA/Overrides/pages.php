<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$tempColumnsPages = array(

    'tx_rkworder_publication' => array (
        'exclude' => 0,
        'displayCond' => 'FIELD:tx_bmpdf2content_is_import:=:1',
		'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication ',
		'config' => array(
			'type' => 'select',
			'renderType' => 'selectSingle',
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
			'foreign_table' => 'tx_rkworder_domain_model_publication',
			'foreign_table_where' => 'AND tx_rkworder_domain_model_publication.deleted = 0 AND tx_rkworder_domain_model_publication.hidden = 0 ORDER BY tx_rkworder_domain_model_publication.title ASC',
            'items' => array(
                array('---', NULL),
            ),
		)
    ),
);


// Add TCA - nothing else
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages',$tempColumnsPages);

// Add field to the existing palette
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', 'tx_rkwbasics_extended', 'tx_rkworder_publication,','after:tx_bmpdf2content_is_import');
