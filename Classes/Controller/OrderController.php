<?php

namespace RKW\RkwOrder\Controller;

use \RKW\RkwBasics\Helper\Common;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use \RKW\RkwOrder\Helper\Stock;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * OrderController
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_ORDER_CREATED_ADMIN = 'afterOrderCreatedAdmin';

    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_ORDER_CREATED_USER = 'afterOrderCreatedUser';

    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_ORDER_DELETED_ADMIN = 'afterOrderDeletedAdmin';

    /**
     * Signal name for use in ext_localconf.php
     *
     * @const string
     */
    const SIGNAL_AFTER_ORDER_DELETED_USER = 'afterOrderDeletedUser';


    /**
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     * @inject
     */
    protected $signalSlotDispatcher;

    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;


    /**
     * orderRepository
     *
     * @var \RKW\RkwOrder\Domain\Repository\OrderRepository
     * @inject
     */
    protected $orderRepository = null;

    /**
     * pagesRepository
     *
     * @var \RKW\RkwOrder\Domain\Repository\PagesRepository
     * @inject
     */
    protected $pagesRepository = null;

    /**
     * FrontendUserRepository
     *
     * @var \RKW\RkwOrder\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRepository;

    /**
     * FrontendUserRepository
     *
     * @var \RKW\RkwRegistration\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRegistrationRepository;

    /**
     * BackendUserRepository
     *
     * @var \RKW\RkwOrder\Domain\Repository\BackendUserRepository
     * @inject
     */
    protected $backendUserRepository;

    /**
     * logged in FrontendUser
     *
     * @var \RKW\RkwOrder\Domain\Model\FrontendUser
     */
    protected $frontendUser = null;


    /**
     * OrderManager
     *
     * @var \RKW\RkwOrder\Orders\OrderManager
     * @inject
     */
    protected $orderManager;


    /**
     * @var \TYPO3\CMS\Core\Log\Logger
     */
    protected $logger;


    /**
     * action newInit
     *
     * @param \RKW\RkwOrder\Domain\Model\Order $newOrder
     * @ignorevalidation $newOrder
     * @return void
     */
    public function newInitAction(\RKW\RkwOrder\Domain\Model\Order $newOrder = null)
    {

        /** @var \RKW\RkwOrder\Domain\Model\Pages $pages */
        $pages = $this->pagesRepository->findByUid($this->getImportedParentPid(intval($GLOBALS['TSFE']->id)));
        if ($publication = $pages->getTxRkworderPublication()) {

            // @toDo: if allowSeries is true, check if the whole series is still available
            $this->view->assignMultiple(
                array(
                    'frontendUser'       => null,
                    'newOrder'           => $newOrder,
                    'pageUid'            => intval($GLOBALS['TSFE']->id),
                    'termsPid'           => intval($this->settings['termsPid']),
                    'publication'        => $publication,
                    'maximumOrderAmount' => Stock::getStock($publication),
                )
            );
        }
    }


    /**
     * action newAjax
     *
     * @param int $pid
     * @return void
     */
    public function newAjaxAction($pid = 0)
    {
        /** @var \RKW\RkwBasics\Helper\Json $jsonHelper */
        $jsonHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwBasics\\Helper\\Json');
        if ($pid > 0) {

            /** @var \RKW\RkwOrder\Domain\Model\Pages $pages */
            $pages = $this->pagesRepository->findByUid($this->getImportedParentPid(intval($pid)));
            if ($publication = $pages->getTxRkworderPublication()) {

                // check if publication is completely out of stock
                if (
                    (!$publication->getSeries())
                    && (Stock::getStock($publication) < 1)
                ) {

                    // remove content of form
                    $jsonHelper->setHtml(
                        'rkw-order-wrap',
                        array(),
                        'replace'
                    );
                }

                // Availability messages behind cache
                $replacements = array(
                    'publication' => $publication,
                );

                $jsonHelper->setHtml(
                    'rkw-order-availability',
                    $replacements,
                    'replace',
                    'Ajax/New/Availability.html'
                );

            }
        }


        /*
         * @toDo: When loading partials via AJAX the object properties are not set!
         * /
        // check if user is logged in
        if (
            (
                ($frontendUser = $this->getFrontendUser())
                || ($frontendUser = $this->getFrontendUserBySession())
            )
            && (\RKW\RkwRegistration\Tools\Registration::validEmail($frontendUser))
        ) {

            $replacements = array (
                'frontendUser' => $frontendUser,
                'newOrder' => \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwOrder\\Domain\\Model\\Order'),
            );

            $jsonHelper->setHtml(
                'rkw-order-user-data-email',
                $replacements,
                'replace',
                'Ajax/New/UserDataEmail.html'
            );
            $jsonHelper->setHtml(
                'rkw-order-user-data',
                $replacements,
                'replace',
                'Ajax/New/UserData.html'
            );

        }
        */

        print (string)$jsonHelper;
        exit();
        //===
    }

    /**
     * action new
     *
     * @param \RKW\RkwOrder\Domain\Model\Order $newOrder
     * @return void
     */
    public function newAction(\RKW\RkwOrder\Domain\Model\Order $newOrder = null)
    {
        /** @var \RKW\RkwOrder\Domain\Model\Pages $pages */
        $pages = $this->pagesRepository->findByUid($this->getImportedParentPid(intval($GLOBALS['TSFE']->id)));
        if ($publication = $pages->getTxRkworderPublication()) {

            $this->view->assignMultiple(
                array(
                    'frontendUser'       => null,
                    'newOrder'           => $newOrder,
                    'termsPid'           => intval($this->settings['termsPid']),
                    'publication'        => $publication,
                    'maximumOrderAmount' => Stock::getStock($publication),
                )
            );
        }
    }


    /**
     * action create
     *
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @param integer $terms
     * @param integer $privacy
     * @return void
     * @throws \RKW\RkwOrder\Exception
     * @throws \RKW\RkwRegistration\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function createAction(\RKW\RkwOrder\Domain\Model\Order $order, $terms = null, $privacy = null)
    {

        try {

            $message = $this->orderManager->createOrder($order, $this->request, $this->getFrontendUser(), $terms, $privacy);
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    $message, 'rkw_order'
                )
            );

        } catch (\RKW\RkwOrder\Exception $exception) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    $exception->getMessage(), 'rkw_order'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $this->forward('new');
            //===
        }

        $this->redirect('new');
        //===
    }


    /**
     * Takes optIn parameters and checks them
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function optInAction()
    {
        $tokenYes = preg_replace('/[^a-zA-Z0-9]/', '', ($this->request->hasArgument('token_yes') ? $this->request->getArgument('token_yes') : ''));
        $tokenNo = preg_replace('/[^a-zA-Z0-9]/', '', ($this->request->hasArgument('token_no') ? $this->request->getArgument('token_no') : ''));
        $userSha1 = preg_replace('/[^a-zA-Z0-9]/', '', $this->request->getArgument('user'));

        /** @var \RKW\RkwRegistration\Tools\Registration $register */
        $register = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwRegistration\\Tools\\Registration');
        $check = $register->checkTokens($tokenYes, $tokenNo, $userSha1, $this->request);

        if ($check == 1) {

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'orderController.message.order_created', 'rkw_order'
                )
            );

        } elseif ($check == 2) {

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'orderController.message.order_canceled', 'rkw_order'
                )
            );

        } else {

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'orderController.error.order_error', 'rkw_order'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
        }

        $this->forward('new');
        //===
    }



    /**
     * Returns the PID of the parent page if pages have been imported via bm_pdf2content
     *
     * @param $pid
     * @return integer
     */
    protected function getImportedParentPid($pid)
    {
        // Check if it is an imported page!
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('bm_pdf2content')) {

            // get PageRepository and rootline
            $repository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
            $rootlinePages = $repository->getRootLine(intval($pid));

            // go through all pages and take the one that has a match in the corresponsing field
            // but only if the current page IS an import sub page!
            if (
                (isset($rootlinePages[count($rootlinePages) - 1]))
                && (isset($rootlinePages[count($rootlinePages) - 1]['tx_bmpdf2content_is_import_sub']))
                && ($rootlinePages[count($rootlinePages) - 1]['tx_bmpdf2content_is_import_sub'] == 1)
            ) {

                foreach ($rootlinePages as $page => $values) {
                    if (
                        ($values['tx_bmpdf2content_is_import'] == 1)
                        && ($values['tx_bmpdf2content_is_import_sub'] == 0)
                    ) {
                        $pid = intval($values['uid']);
                        break;
                        //===
                    }
                }
            }

        }

        return $pid;
        //===
    }


    /**
     * Returns current logged in user object
     *
     * @return \RKW\RkwRegistration\Domain\Model\FrontendUser|null
     */
    protected function getFrontendUser()
    {

        if (!$this->frontendUser) {

            $frontendUser = $this->frontendUserRepository->findByUidNoAnonymous($this->getFrontendUserId());
            if ($frontendUser instanceof \RKW\RkwRegistration\Domain\Model\FrontendUser) {
                $this->frontendUser = $frontendUser;
            }
        }

        return $this->frontendUser;
        //===
    }


    /**
     * Gets FE-User data from session
     *
     * @return \RKW\RkwOrder\Domain\Model\FrontendUser|null
     */
    protected function getFrontendUserBySession()
    {
        $frontendUser = null;

        /**  @var $feAuth \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        if (
            (!$this->getFrontendUser())
            && ($feAuth = $GLOBALS['TSFE']->fe_user)
            && ($dataOfNotLoggedUser = $feAuth->getKey('ses', 'rkwOrderUserData'))
            && (is_array($dataOfNotLoggedUser))
        ) {

            /** @var \RKW\RkwOrder\Domain\Model\FrontendUser $frontendUser */
            $frontendUser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwOrder\\Domain\\Model\\FrontendUser');
            $frontendUser->setTxRkwRegistrationGender($dataOfNotLoggedUser['tx_rkwregistration_gender']);
            $frontendUser->setFirstName($dataOfNotLoggedUser['first_name']);
            $frontendUser->setLastName($dataOfNotLoggedUser['last_name']);
            $frontendUser->setCompany($dataOfNotLoggedUser['company']);
            $frontendUser->setAddress($dataOfNotLoggedUser['address']);
            $frontendUser->setZip($dataOfNotLoggedUser['zip']);
            $frontendUser->setCity($dataOfNotLoggedUser['city']);
            $frontendUser->setEmail($dataOfNotLoggedUser['email']);
        }

        return $frontendUser;
        //===
    }


    /**
     * Sets FE-User data into session
     *
     * @param \RKW\RkwOrder\Domain\Model\Order $newOrder
     * @return void
     */
    protected function setFrontendUserBySession(\RKW\RkwOrder\Domain\Model\Order $newOrder)
    {
        /**  @var $feAuth \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        if (
            (!$this->getFrontendUser())
            && ($feAuth = $GLOBALS['TSFE']->fe_user)
        ) {
            $userData = array(
                'tx_rkwregistration_gender' => $newOrder->getGender(),
                'first_name'                => $newOrder->getFirstName(),
                'last_name'                 => $newOrder->getLastName(),
                'company'                   => $newOrder->getCompany(),
                'address'                   => $newOrder->getAddress(),
                'zip'                       => $newOrder->getZip(),
                'city'                      => $newOrder->getCity(),
                'email'                     => $newOrder->getEmail(),
            );

            $feAuth->setKey('ses', 'rkwOrderUserData', $userData);
        }
    }

    /**
     * Id of logged User
     *
     * @return integer|null
     */
    protected function getFrontendUserId()
    {
        // is $GLOBALS set?
        if (
            ($GLOBALS['TSFE'])
            && ($GLOBALS['TSFE']->loginUser)
            && ($GLOBALS['TSFE']->fe_user->user['uid'])
        ) {
            return intval($GLOBALS['TSFE']->fe_user->user['uid']);
            //===
        }

        return null;
        //===
    }


    /**
     * Remove ErrorFlashMessage
     *
     * @see \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::getErrorFlashMessage()
     */
    protected function getErrorFlashMessage()
    {
        return false;
        //===
    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     */
    protected function getSettings($which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS)
    {
        return Common::getTyposcriptConfiguration('Rkworder', $which);
        //===
    }


    /**
     * Returns logger instance
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger()
    {

        if (!$this->logger instanceof \TYPO3\CMS\Core\Log\Logger) {
            $this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        }

        return $this->logger;
        //===
    }


}