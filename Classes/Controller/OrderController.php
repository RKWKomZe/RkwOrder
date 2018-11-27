<?php

namespace RKW\RkwOrder\Controller;

use \RKW\RkwBasics\Helper\Common;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

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

        $this->view->assignMultiple(
            array(
                'frontendUser'      => null,
                'newOrder'          => $newOrder,
                'termsPid'          => intval($this->settings['termsPid']),
                'seriesTitle'       => $pages->getTxRkwbasicsSeries() ? $pages->getTxRkwbasicsSeries()->getName() : '',
                'allowSeries'       => $this->settings['order']['allowSeries'],
                'allowSubscription' => $this->settings['order']['allowSubscription'],
                'pages'             => $pages,
                'pageUid'           => intval($GLOBALS['TSFE']->id),
            )
        );
    }


    /**
     * action newAjax
     *
     * @return void
     */
    public function newAjaxAction()
    {
        /** @var \RKW\RkwBasics\Helper\Json $jsonHelper */
        $jsonHelper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwBasics\\Helper\\Json');

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

        $this->view->assignMultiple(
            array(
                'newOrder'          => $newOrder,
                'termsPid'          => intval($this->settings['termsPid']),
                'seriesTitle'       => $pages->getTxRkwbasicsSeries() ? $pages->getTxRkwbasicsSeries()->getName() : '',
                'allowSeries'       => $this->settings['order']['allowSeries'],
                'allowSubscription' => $this->settings['order']['allowSubscription'],
                'pages'             => $pages,
            )
        );

    }


    /**
     * action create
     *
     * @param \RKW\RkwOrder\Domain\Model\Order $newOrder
     * @param integer $terms
     * @param integer $privacy
     * @return void
     * @throws \RKW\RkwRegistration\Exception
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function createAction(\RKW\RkwOrder\Domain\Model\Order $newOrder, $terms = null, $privacy = null)
    {
        // check if terms are checked
        if (
            (!$terms)
            && (!$this->getFrontendUser())
        ) {

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'orderController.error.accept_terms', 'rkw_order'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );

            $this->forward('new');
            //===
        }

        if (!$privacy) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'registrationController.error.accept_privacy', 'rkw_registration'
                ),
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            $this->forward('new');
            //===
        }

        // 1. get page and pageTitle / pageSubtitle of it
        /** @var \RKW\RkwOrder\Domain\Model\Pages $pages */
        $pages = $this->pagesRepository->findByUid($this->getImportedParentPid(intval($GLOBALS['TSFE']->id)));

        $newOrder->setPageTitle($pages->getTitle());
        $newOrder->setPageSubtitle($pages->getSubtitle());
        $newOrder->setPages($pages);

        if ($pages->getTxRkwbasicsSeries()) {
            $newOrder->setSeriesTitle($pages->getTxRkwbasicsSeries()->getName());
        }

        // 2. register order
        // 2.1 if user is logged in
        if (
            ($feUser = $this->getFrontendUser())
            && (\RKW\RkwRegistration\Tools\Registration::validEmail($this->getFrontendUser()))
        ) {

            // update FE-User-Data if none is set so long
            if (!$feUser->getTxRkwregistrationGender()) {
                $feUser->setTxRkwregistrationGender($newOrder->getGender());
            }
            if (!$feUser->getFirstName()) {
                $feUser->setFirstName($newOrder->getFirstName());
            }
            if (!$feUser->getLastName()) {
                $feUser->setLastName($newOrder->getLastName());
            }
            if (!$feUser->getCompany()) {
                $feUser->setCompany($newOrder->getCompany());
            }
            if (!$feUser->getAddress()) {
                $feUser->setAddress($newOrder->getAddress());
            }
            if (!$feUser->getZip()) {
                $feUser->setZip($newOrder->getZip());
            }
            if (!$feUser->getCity()) {
                $feUser->setCity($newOrder->getCity());
            }

            $this->frontendUserRepository->update($feUser);
            $this->persistenceManager->persistAll();

            // connect order with FE-User and save order
            $newOrder->setFrontendUser($feUser);
            $this->finalSaveOrder($newOrder);

            // add privacy info
            \RKW\RkwRegistration\Tools\Privacy::addPrivacyData($this->request, $feUser, $newOrder, 'new order');

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'orderController.message.order_created', 'rkw_order'
                )
            );


            // 2.2 if user is not logged in - or is logged in and has no valid email (e.g. when having registered via Facebook or Twitter)
            // A not logged user always has to confirm his orders. If the email already exists, there will be no new
            // user created
        } else {

            // check if email is valid
            if (!\RKW\RkwRegistration\Tools\Registration::validEmail($newOrder->getEmail())) {
                $this->addFlashMessage(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'orderController.error.no_valid_email', 'rkw_order'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );

                $this->forward('new');
                //===
            }

            // check if email is not already used - relevant for logged in users with no email-address (e.g. via Facebook or Twitter)
            if (
                ($this->getFrontendUser())
                && (!\RKW\RkwRegistration\Tools\Registration::validEmailUnique($newOrder->getEmail(), $this->getFrontendUser()))
            ) {

                $this->addFlashMessage(
                    \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'orderController.error.email_already_in_use', 'rkw_order'
                    ),
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );

                $this->forward('new');
                //===
            }

            // register new user or simply send opt-in to existing user
            /** @var \RKW\RkwRegistration\Tools\Registration $registration */
            $registration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwRegistration\\Tools\\Registration');
            $registration->register(
                array(
                    'tx_rkwregistration_gender' => $newOrder->getGender(),
                    'first_name'                => $newOrder->getFirstName(),
                    'last_name'                 => $newOrder->getLastName(),
                    'company'                   => $newOrder->getCompany(),
                    'address'                   => $newOrder->getAddress(),
                    'zip'                       => $newOrder->getZip(),
                    'city'                      => $newOrder->getCity(),
                    'email'                     => $newOrder->getEmail(),
                    'username'                  => ($this->getFrontendUser() ? $this->getFrontendUser()->getUsername() : $newOrder->getEmail()),
                ),
                false,
                $newOrder,
                'rkwOrder',
                $this->request
            );

            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'orderController.message.order_created_email', 'rkw_order'
                )
            );

            // save data of not logged user in session (redmine Ticket #2559)
            $this->setFrontendUserBySession($newOrder);
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
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
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


    //=================================================================================================

    /**
     * Creates an order - used by SignalSlot
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $feUser
     * @param \RKW\RkwRegistration\Domain\Model\Registration $registration
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function createOrder(\RKW\RkwRegistration\Domain\Model\FrontendUser $feUser, \RKW\RkwRegistration\Domain\Model\Registration $registration)
    {

        // 1. get order from registration and save it
        if (
            ($newOrder = $registration->getData())
            && ($newOrder instanceof \RKW\RkwOrder\Domain\Model\Order)
        ) {

            // update FE-User-Data if none is set so long
            // only relevant for existing users that are NOT logged in!
            if (
                (!$feUser->getTxRkwregistrationGender())
                || ($feUser->getTxRkwregistrationGender() == 99)
            ) {
                $feUser->setTxRkwregistrationGender($newOrder->getGender());
            }

            if (!$feUser->getFirstName()) {
                $feUser->setFirstName($newOrder->getFirstName());
            }

            if (!$feUser->getLastName()) {
                $feUser->setLastName($newOrder->getLastName());
            }

            if (!$feUser->getCompany()) {
                $feUser->setCompany($newOrder->getCompany());
            }

            if (!$feUser->getAddress()) {
                $feUser->setAddress($newOrder->getAddress());
            }

            if (!$feUser->getZip()) {
                $feUser->setZip($newOrder->getZip());
            }

            if (!$feUser->getCity()) {
                $feUser->setCity($newOrder->getCity());
            }

            if (!\RKW\RkwRegistration\Tools\Registration::validEmail($feUser->getEmail())) {
                $feUser->setEmail($newOrder->getEmail());
            }

            $this->frontendUserRegistrationRepository->update($feUser);
            $this->persistenceManager->persistAll();

            $newOrder->setFrontendUser($feUser);
            $this->finalSaveOrder($newOrder);
        }
    }


    /**
     * Removes all open orders of a FE-User - used by Signal-Slot
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @return void
     */
    public function removeAllOfUserSignalSlot(\RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser)
    {
        try {
            $orders = $this->orderRepository->findOpenByFrontendUser($frontendUser);
            if ($orders) {

                /** @var \RKW\RkwOrder\Domain\Model\Order $order $order */
                foreach ($orders as $order) {

                    // 1. delete order
                    $this->orderRepository->remove($order);

                    /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
                    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

                    /** @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager $persistenceManager */
                    $persistenceManager = $objectManager->get('TYPO3\\CMS\Extbase\\Persistence\\Generic\\PersistenceManager');
                    $persistenceManager->persistAll();

                    // 2. send final confirmation mail to user
                    $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_ORDER_DELETED_USER, array($frontendUser, $order));

                    // 3. Admin mail
                    $backendUsers = array();
                    $settings = $this->getSettings();
                    if ($backendUserId = intval($settings['backendUserIdForMails'])) {
                        $admin = $this->backendUserRepository->findByUid($backendUserId);
                        if (
                            ($admin)
                            && ($admin->getEmail())
                        ) {
                            $backendUsers[] = $admin;
                        }
                    }
                    $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_ORDER_DELETED_ADMIN, array($backendUsers, $frontendUser, $order));
                    $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, sprintf('Deleted order with uid %s of user with uid %s via signal-slot.', $order->getUid(), $frontendUser->getUid()));

                }
            }
        } catch (\Exception $e) {
            $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::ERROR, sprintf('Error while deleting orders of user via signal-slot: %s', $e->getMessage()));
        }
    }


    /**
     * finalSaveOrder
     * Adds the order finally to database and sends information mails to user and admin
     * This function is used bei the "createOrderAction" and "createOrder"-Function
     *
     * @param \RKW\RkwOrder\Domain\Model\Order $newOrder
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function finalSaveOrder(\RKW\RkwOrder\Domain\Model\Order $newOrder)
    {
        // save it
        $this->orderRepository->add($newOrder);

        $this->persistenceManager->persistAll();

        // 2. send final confirmation mail to user
        $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_ORDER_CREATED_USER, array($newOrder->getFrontendUser(), $newOrder));

        // 3. send information mail to admins
        $adminUidList = explode(',', $this->settings['mail']['backendUser']);
        $backendUsers = array();
        foreach ($adminUidList as $adminUid) {
            if ($adminUid) {
                $admin = $this->backendUserRepository->findByUid($adminUid);
                if ($admin) {
                    $backendUsers[] = $admin;
                }
            }
        }

        // 4. fallback-handling
        if (
            (count($backendUsers) < 1)
            && ($backendUserFallback = intval($this->settings['backendUserIdForMails']))
        ) {
            $admin = $this->backendUserRepository->findByUid($backendUserFallback);
            if (
                ($admin)
                && ($admin->getEmail())
            ) {
                $backendUsers[] = $admin;
            }

        }

        $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_ORDER_CREATED_ADMIN, array($backendUsers, $newOrder));

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
     * @return \RKW\RkwRegistration\Domain\Model\FrontendUser|NULL
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
     * @return \RKW\RkwOrder\Domain\Model\FrontendUser | null
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
     * @return integer|NULL
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