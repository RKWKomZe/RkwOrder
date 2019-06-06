<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'RKW.' . $_EXTKEY,
	'Rkworder',
	array(
		'Order' => 'newInit, newAjax, new, create, optIn',
	),
	// non-cacheable actions
	array(
		'Order' => 'newAjax, new, create, optIn',
	)
);

// register command controller (cronjob)
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'RKW\\RkwOrder\\Controller\\OrderCommandController';

/**
 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
 */
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
$signalSlotDispatcher->connect(
	'RKW\\RkwRegistration\\Tools\\Registration',
	\RKW\RkwRegistration\Tools\Registration::SIGNAL_AFTER_CREATING_OPTIN_EXISTING_USER  . 'RkwOrder',
	'RKW\\RkwOrder\\Service\\RkwMailService',
	'handleOptInRequestEvent'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwRegistration\\Tools\\Registration',
	\RKW\RkwRegistration\Tools\Registration::SIGNAL_AFTER_CREATING_OPTIN_USER  . 'RkwOrder',
	'RKW\\RkwOrder\\Service\\RkwMailService',
	'handleOptInRequestEvent'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwRegistration\\Tools\\Registration',
	\RKW\RkwRegistration\Tools\Registration::SIGNAL_AFTER_USER_REGISTER_GRANT . 'RkwOrder',
	'RKW\RkwOrder\Orders\OrderManager',
	'saveOrderSignalSlot'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwOrder\\Controller\\OrderController',
	\RKW\RkwOrder\Controller\OrderController::SIGNAL_AFTER_ORDER_CREATED_USER,
	'RKW\\RkwOrder\\Service\\RkwMailService',
	'confirmationOrderUser'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwOrder\\Controller\\OrderController',
	\RKW\RkwOrder\Controller\OrderController::SIGNAL_AFTER_ORDER_CREATED_ADMIN,
	'RKW\\RkwOrder\\Service\\RkwMailService',
	'confirmationOrderAdmin'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwRegistration\\Tools\\Registration',
	\RKW\RkwRegistration\Tools\Registration::SIGNAL_AFTER_DELETING_USER,
    'RKW\RkwOrder\Orders\OrderManager',
    'removeAllOrdersOfFrontendUserSignalSlot'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwOrder\\Controller\\OrderController',
	\RKW\RkwOrder\Controller\OrderController::SIGNAL_AFTER_ORDER_DELETED_USER,
	'RKW\\RkwOrder\\Service\\RkwMailService',
	'deleteOrderUser'
);

$signalSlotDispatcher->connect(
	'RKW\\RkwOrder\\Controller\\OrderController',
	\RKW\RkwOrder\Controller\OrderController::SIGNAL_AFTER_ORDER_DELETED_ADMIN,
	'RKW\\RkwOrder\\Service\\RkwMailService',
	'deleteOrderAdmin'
);

// set logger
$GLOBALS['TYPO3_CONF_VARS']['LOG']['RKW']['RkwOrder']['writerConfiguration'] = array(

    // configuration for WARNING severity, including all
    // levels with higher severity (ERROR, CRITICAL, EMERGENCY)
    \TYPO3\CMS\Core\Log\LogLevel::DEBUG => array(
        // add a FileWriter
        'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => array(
            // configuration for the writer
            'logFile' => 'typo3temp/logs/tx_rkworder.log'
        )
    ),
);