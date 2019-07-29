<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkworder_domain_model_orderproduct', 'EXT:rkw_order/Resources/Private/Language/locallang_csh_tx_rkworder_domain_model_orderproduct.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkworder_domain_model_orderproduct');
$GLOBALS['TCA']['tx_rkworder_domain_model_orderproduct'] = [
	'ctrl' => [
        'hideTable' => true,
        'title'	=> 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_orderproduct',
        'label' => 'product',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
        'delete' => 'deleted',

        'searchFields' => 'order, product, amount, is_pre_order',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('rkw_order') . 'Resources/Public/Icons/tx_rkworder_domain_model_orderproduct.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'order, product, amount, is_pre_order',
	],
	'types' => [
		'1' => ['showitem' => 'order, product, amount, is_pre_order'],
	],
	'palettes' => [
		'1' => ['showitem' => ''],
	],
	'columns' => [

        'order' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        'product' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_orderproduct.product',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rkworder_domain_model_product',
                'foreign_table_where' => 'AND tx_rkworder_domain_model_product.hidden = 0 AND tx_rkworder_domain_model_product.deleted = 0',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],

        'amount' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_orderproduct.amount',
			'config' => [
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			]
		],

        'is_pre_order' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_orderproduct.isPreOrder',
            'config' => [
                'type' => 'check',
                'readOnly' => 0,
            ],
        ],
	],
];