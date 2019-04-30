<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress',
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
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('rkw_order') . 'Resources/Public/Icons/tx_rkworder_domain_model_shippingaddress.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden, order, title, gender, company, first_name, last_name, address, zip, city',
	),
	'types' => array(
		'1' => array('showitem' => 'hidden;;1, order, title, gender, company, first_name, last_name, address, zip, city'),
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

        'gender' => array(
			'label'=>'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.gender',
			'exclude' => 0,
			'config'=>array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'minitems' => 0,
				'maxitems' => 1,
				'default' => 99,
				'items' => array(
					array('LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.gender.I.man', '0'),
					array('LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.gender.I.woman', '1'),
					array('LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.gender.I.neutral', '99'),
				),
			)
		),
		'title' => array(
			'label'=>'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.title',
			'exclude' => 0,
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_rkwregistration_domain_model_title',
				'foreign_table_where' => 'AND tx_rkwregistration_domain_model_title.hidden = 0 AND tx_rkwregistration_domain_model_title.deleted = 0 AND tx_rkwregistration_domain_model_title.is_title_after = 0 ORDER BY name ASC',
				'minitems' => 0,
				'maxitems' => 1,
				'items' => array(
					array('LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.title.please_choose', 0),
				),
			),
		),
		'first_name' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.first_name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'last_name' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.last_name',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'company' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.company',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'address' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.address',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'zip' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.zip',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'city' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_shippingaddress.city',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),

		
	),
);