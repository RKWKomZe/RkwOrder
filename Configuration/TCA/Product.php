<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rkworder_domain_model_order', 'EXT:rkw_order/Resources/Private/Language/locallang_csh_tx_rkworder_domain_model_order.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rkworder_domain_model_product');
$GLOBALS['TCA']['tx_rkworder_domain_model_product'] = [
	'ctrl' => [
		'title'	=> 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product',
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
		'requestUpdate' => 'product_parent',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
		],
		'searchFields' => 'itle, subtitle, stock, ordered_external, page, product_parent, bundle_only, subscription_only, backend_user, admin_email,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('rkw_order') . 'Resources/Public/Icons/tx_rkworder_domain_model_product.gif'
	],
	'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, subtitle, image, stock, ordered_external, page, product_parent, bundle_only, subscription_only, backend_user, admin_email',
	],
	'types' => [
		'1' => ['showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, title, subtitle, image, stock, ordered_external, page, product_parent, bundle_only, subscription_only, backend_user, admin_email, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'],
	],
	'palettes' => [
		'1' => ['showitem' => ''],
	],
	'columns' => [
	
		'sys_language_uid' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => [
					['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
					['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0]
				],
                'default' => 0
            ],
        ],
		'l10n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_rkworder_domain_model_product',
				'foreign_table_where' => 'AND tx_rkworder_domain_model_product.pid=###CURRENT_PID### AND tx_rkworder_domain_model_product.sys_language_uid IN (-1,0)',
			],
		],
		'l10n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],
		'title' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.title',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'subtitle' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.subtitle',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'stock' => [
            'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.stock',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,int,required',
                'default' => 100
			],
		],
        'ordered_external' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.ordered_external',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'readOnly' => true
            ],
        ],
		'bundle_only' => [
            'displayCond' => 'FIELD:product_parent:REQ:FALSE',
			'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.bundle_only',
			'config' => [
				'type' => 'check',
				'default' => 0
			]
		],
		'subscription_only' => [
            'displayCond' => 'FIELD:product_parent:REQ:FALSE',
            'exclude' => 0,
			'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.subscription_only',
			'config' => [
				'type' => 'check',
				'default' => 0
			]
		],
        'product_parent' => [
            'onChange' => 'reload',
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.product_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_rkworder_domain_model_product',
                'foreign_table_where' => 'AND tx_rkworder_domain_model_product.deleted = 0 AND tx_rkworder_domain_model_product.hidden = 0 AND tx_rkworder_domain_model_product.product_parent = "" AND tx_rkworder_domain_model_product.uid != ###THIS_UID###  ORDER BY tx_rkworder_domain_model_product.title ASC',
            ]
        ],
        'page' => [
            'exclude' => false,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.page',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'int,required',
                'wizards' => [
                    '_PADDING' => 2,
                    'link' => [
                        'type' => 'popup',
                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:header_link_formlabel',
                        'icon' => 'link_popup.gif',
                        'module' => [
                            'name' => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard',
                            ]
                        ],
                        'JSopenParams' => 'height=400,width=550,status=0,menubar=0,scrollbars=1',
                        'params' => [
                            // List of tabs to hide in link window. Allowed values are:
                            // file, mail, page, spec, folder, url
                            'blindLinkOptions' => 'mail,file,spec,folder,url',

                            // allowed extensions for file
                            //'allowedExtensions' => 'mp3,ogg',
                        ]
                    ]
                ],
                'softref' => 'typolink'
            ],
        ],
        'image' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'maxitems' => 1,

                    // Use the imageoverlayPalette instead of the basicoverlayPalette
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ]
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],        
        'backend_user' => [
            'displayCond' => 'FIELD:product_parent:REQ:FALSE',
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.backend_user',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 99,
                'foreign_table' => 'be_users',
                'foreign_table_where' => 'AND be_users.deleted = 0 AND be_users.disable = 0 ORDER BY be_users.username ASC',
            ]
        ],
        'admin_email' => [
            'displayCond' => 'FIELD:product_parent:REQ:FALSE',
            'exclude' => 0,
            'label' => 'LLL:EXT:rkw_order/Resources/Private/Language/locallang_db.xlf:tx_rkworder_domain_model_product.admin_email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,email'
            ],
        ],
    ],
];