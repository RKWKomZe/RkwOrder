<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order',
        'label' => 'last_name',
        'label_alt' => 'last_name,first_name',
        'label_alt_force' => 1,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'amount,first_name,last_name,address,zip,city,email,frontend_user,pages,remark,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('rkw_order') . 'Resources/Public/Icons/tx_rkworder_domain_model_order.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden, status, pages, publication, send_series, subscribe, amount, email, frontend_user, shipping_address, remark',
	),
	'types' => array(
		'1' => array('showitem' => 'hidden;;1, status, pages, publication, send_series, subscribe, amount, email, frontend_user, shipping_address, remark'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	

		'hidden' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'status' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.status',
			'config' => array(
				'type' => 'check',
				'default' => 0,
				'items' => array(
					'1' => array(
						'0' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.status.I.sent'
					)
				)
			),
		),
        'pages' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.pages',
            /*'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'pages',
                'foreign_table_where' => 'AND pages.hidden = 0 AND pages.deleted = 0',
                'minitems' => 1,
                'maxitems' => 1,
                'readOnly' =>1,
            ),*/
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,int',
                'readOnly' =>1,
            ),
        ),
        'publication' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.publication',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rkworder_domain_model_publication',
                'foreign_table_where' => 'AND tx_rkworder_domain_model_publication.hidden = 0 AND tx_rkworder_domain_model_publication.deleted = 0',
                'minitems' => 1,
                'maxitems' => 1,
                'readOnly' =>1,
            ),
        ),
        'send_series' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.send_series',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'subscribe' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.subscribe',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'amount' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.amount',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'frontend_user' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.frontend_user',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'foreign_table_where' => 'AND fe_users.disable = 0 AND fe_users.deleted = 0 ORDER BY username ASC',
				'minitems' => 1,
				'maxitems' => 1,
				'readOnly' =>1,
			),
		),
        'email' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.email',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'shipping_address' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.shipping_address',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_rkworder_domain_model_shippingaddress',
                'foreign_table_where' => 'AND tx_rkworder_domain_model_shippingaddress.deleted = 0 AND tx_rkworder_domain_model_shippingaddress.hidden = 0 ORDER BY tx_rkworder_domain_model_shippingaddress.address ASC',
                'minitems' => 1,
                'maxitems' => 1,
                'readOnly' => 1,
            ),
        ),
		'remark' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_order.remark',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		
	),
);