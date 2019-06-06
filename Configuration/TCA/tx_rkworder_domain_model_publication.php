<?php

return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication',
		'label' => 'title',
		'label_alt' => 'subtitle',
		'label_alt_force' => 1,
		'default_sortby' => 'ORDER BY title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'requestUpdate' => 'series',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'title,subtitle,admin_email,stock,allow_subscription,bundle_only, series,backend_user,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('rkw_order') . 'Resources/Public/Icons/tx_rkworder_domain_model_publication.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, subtitle, stock, allow_subscription, bundle_only, series, backend_user, admin_email',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title, subtitle, stock, allow_subscription, bundle_only, series, backend_user, admin_email, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
                'default' => 0
            ),
        ),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_rkworder_domain_model_publication',
				'foreign_table_where' => 'AND tx_rkworder_domain_model_publication.pid=###CURRENT_PID### AND tx_rkworder_domain_model_publication.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'subtitle' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication.subtitle',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'admin_email' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication.admin_email',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,email'
			),
		),
		'stock' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication.stock',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,int,required',
                'default' => 100
			),
		),

		'bundle_only' => array(
            'displayCond' => 'FIELD:series:REQ:TRUE',
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication.bundle_only',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'allow_subscription' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication.allow_subscription',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'series' => array(
            'onChange' => 'reload',
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication.series',
			'config' => array(
				'items' => array(
					array('', 0),
				),
				'type' => 'select',
				'renderType' => 'selectSingle',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'foreign_table' => 'tx_rkwbasics_domain_model_series',
				'foreign_table_where' => 'AND tx_rkwbasics_domain_model_series.deleted = 0 AND tx_rkwbasics_domain_model_series.hidden = 0 ORDER BY tx_rkwbasics_domain_model_series.name ASC',
			)
		),
		'backend_user' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_publication.backend_user',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 99,
				'foreign_table' => 'be_users',
				'foreign_table_where' => 'AND be_users.deleted = 0 AND be_users.disable = 0 ORDER BY be_users.username ASC',
			)
		),
        'ordered' => array(
            'exclude' => 0,
            'config' => array(
                'type' => 'passthrough',
            ),
        ),

        'ordered_external' => array(
            'exclude' => 0,
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
    ),
);