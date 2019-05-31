<?php

namespace RKW\RkwOrder\Orders;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \RKW\RkwOrder\Exception;

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
 * Class OrderManager
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderManager implements \TYPO3\CMS\Core\SingletonInterface
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
     * Create Order
     *
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser|null $frontendUser
     * @param bool $terms
     * @param bool $privacy
     * @return bool
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrder (\RKW\RkwOrder\Domain\Model\Order $order, \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser = null, $terms = false, $privacy = false)
    {

        // check terms if user is not logged in
        if (
            (! $terms)
            && (
                (! $frontendUser)
                || ($frontendUser->_isNew())
            )
        ) {
            throw new Exception('orderManager.error.acceptTerms');
            //===
        }

        // check privacy flag
        if (! $privacy) {
            throw new Exception('orderManager.error.acceptPrivacy');
            //===
        }

        // check given e-mail
        if (! \RKW\RkwRegistration\Tools\Registration::validEmail($order->getEmail())) {
            throw new Exception('orderManager.error.invalidEmail');
            //===
        }

        // check for shippingAddress
        if (! $order->getShippingAddress()) {
            throw new Exception('orderManager.error.noShippingAddress');
            //===
        }


        // handling for existing and logged in users
        if (
            ($frontendUser)
            && (! $frontendUser->_isNew())
        ) {


        // handling for new users
        } else {


        }

        return true;
        //===


    }


    /**
     * saveOrder
     *
     * Adds the order finally to database and sends information mails to user and admin
     * This function is used bei the "createOrderAction" and "createOrder"-Function
     *
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @return bool
     * @throws \RKW\RkwOrder\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function saveOrder (\RKW\RkwOrder\Domain\Model\Order $order, \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser)
    {

        // check order
        if (! $order->_isNew()) {
            throw new Exception('orderManager.error.orderAlreadyPersisted');
            //===
        }

        // check frontendUser
        if ($frontendUser->_isNew()) {
            throw new Exception('orderManager.error.frontendUserNotPersisted');
            //===
        }

        // check shippingAddress
        if (! $order->getShippingAddress()) {
            throw new Exception('orderManager.error.noShippingAddress');
            //===
        }

        // add frontendUser to order and shippingAddress
        $order->setFrontendUser($frontendUser);
        $order->getShippingAddress()->setFrontendUser($frontendUser);

        // save it
        $this->orderRepository->add($order);
        $this->persistenceManager->persistAll();

        return true;
        //===

        // 2. send final confirmation mail to user
        $this->signalSlotDispatcher->dispatch(__CLASS__, self::SIGNAL_AFTER_ORDER_CREATED_USER, array($newOrder->getFrontendUser(), $newOrder));

                // 3. send information mail to admins
        $backendUsers = array();

        /** @var \RKW\RkwOrder\Domain\Model\Pages $pages */
        $pages = $this->pagesRepository->findByUid($this->getImportedParentPid(intval($GLOBALS['TSFE']->id)));
        /**    @var \RKW\RkwOrder\Domain\Model\Publication $publication */
        $publication = $pages->getTxRkworderPublication();

        if (!count($publication->getBackendUser())) {
            if ($publication->getAdminEmail()) {
                $admin = $this->backendUserRepository->findByEmail($publication->getAdminEmail());
                if ($admin) {
                    $backendUsers[] = $admin;
                }
            }
        } else {
            $backendUsers = $publication->getBackendUser()->toArray();
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

    //=================================================================================================

    /**
     * Intermediate function for saving of orders - used by SignalSlot
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwRegistration\Domain\Model\Registration $registration
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function saveOrderSignalSlot(\RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser, \RKW\RkwRegistration\Domain\Model\Registration $registration)
    {
        // get order from registration
        if (
            ($order = $registration->getData())
            && ($order instanceof \RKW\RkwOrder\Domain\Model\Order)
        ) {

            $this->saveOrder($order, $frontendUser);
        }
    }




}