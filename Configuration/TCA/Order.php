<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkworder_domain_model_order', 'EXT:rkw_order/Resources/Private/Language/locallang_csh_tx_rkworder_domain_model_order.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkworder_domain_model_order');
$GLOBALS['TCA']['tx_rkworder_domain_model_order'] = [
	'ctrl' => [
		'title'	=> 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order',
        'label' => 'last_name',
        'label_alt' => 'last_name,first_name',
        'label_alt_force' => 1,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
		],
		'searchFields' => 'amount,first_name,last_name,address,zip,city,email,frontend_user,pages,remark,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('rkw_order') . 'Resources/Public/Icons/tx_rkworder_domain_model_order.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'hidden, status, product, send_series, subscribe, amount, email, frontend_user, shipping_address, remark',
	],
	'types' => [
		'1' => ['showitem' => 'hidden;;1, status, product, send_series, subscribe, amount, email, frontend_user, shipping_address, remark'],
	],
	'palettes' => [
		'1' => ['showitem' => ''],
	],
	'columns' => [
	

		'hidden' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],
		'status' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.status',
			'config' => [
				'type' => 'check',
				'default' => 0,
				'items' => [
					'1' => [
						'0' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.status.I.sent'
					]
				]
			],
		],
        'product' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.product',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_rkworder_domain_model_product',
                'foreign_table_where' => 'AND tx_rkworder_domain_model_product.hidden = 0 AND tx_rkworder_domain_model_product.deleted = 0',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 99,
                'readOnly' =>1,
            ],
        ],
        'send_series' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.send_series',
            'config' => [
                'type' => 'check',
            ],
        ],
        'subscribe' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.subscribe',
            'config' => [
                'type' => 'check',
            ],
        ],
        'amount' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.amount',
			'config' => [
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			]
		],
		'frontend_user' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.frontend_user',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'foreign_table_where' => 'AND fe_users.disable = 0 AND fe_users.deleted = 0 ORDER BY username ASC',
				'minitems' => 1,
				'maxitems' => 1,
				'readOnly' =>1,
			],
		],
        'email' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'shipping_address' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.shipping_address',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rkwregistration_domain_model_shippingaddress',
                'foreign_table_where' => 'AND tx_rkwregistration_domain_model_shippingaddress.deleted = 0 AND tx_rkwregistration_domain_model_shippingaddress.hidden = 0 ORDER BY tx_rkwregistration_domain_model_shippingaddress.address ASC',
                'minitems' => 1,
                'maxitems' => 1,
                'readOnly' => 1,
            ],
        ],
		'remark' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.remark',
			'config' => [
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			]
		],
	],
];