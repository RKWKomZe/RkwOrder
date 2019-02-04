<?php

namespace RKW\RkwOrder\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

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
 * Class OrderRepository
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Find all orders by uid, even if they are hidden
     * Used by SOAP-API
     *
     * @param integer $uid
     * @return object
     */
    public function findByUidAll($uid)
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIncludeDeleted(true);
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        $query->matching(
            $query->equals('uid', intval($uid))
        );

        return $query->execute()->getFirst();
        //===
    }


    /**
     * Find all orders that have been updated recently
     *
     * @api Used by RKW Soap
     * @param integer $timestamp
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByTimestamp($timestamp)
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIncludeDeleted(true);
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        $query->matching(
            $query->greaterThanOrEqual('tstamp', intval($timestamp))
        );
        $query->setOrderings(array('tstamp' => QueryInterface::ORDER_ASCENDING));

        return $query->execute();
        //===
    }


    /**
     * Find all orders that have been updated recently
     * Used by delete Signal-Slot
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findOpenByFrontendUser($frontendUser)
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        $query->matching(
            $query->logicalAnd(
                $query->equals('status', 0),
                $query->equals('frontendUser', $frontendUser)
            )
        );

        return $query->execute();
        //===
    }


    /**
     * finds all order that are older than $cleanupTimestamp
     * Used for cleanup via command-controller
     *
     * @param integer $cleanupTimestamp
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllOldOrder($cleanupTimestamp)
    {

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        $query->getQuerySettings()->setIncludeDeleted(true);

        $query->matching(
            $query->logicalAnd(
                $query->lessThanOrEqual('tstamp', $cleanupTimestamp),
                $query->equals('deleted', 1)
            )
        );

        return $query->execute();
        //===
    }


    /**
     * deleteBySelf
     * Used for cleanup via command-controller
     *
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @return void
     */
    public function deleteBySelf(\RKW\RkwOrder\Domain\Model\Order $order)
    {

        $GLOBALS['TYPO3_DB']->sql_query('
			DELETE FROM tx_rkworder_domain_model_order
			WHERE uid = ' . intval($order->getUid()) . '
		');

        return;
        //===
    }

}