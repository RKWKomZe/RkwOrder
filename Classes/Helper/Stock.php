<?php

namespace RKW\RkwOrder\Helper;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * Class Stock
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Stock implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Returns remaining max order amount
     *
     * @param \RKW\RkwOrder\Domain\Model\Publication $publication
     * @return integer
     */
    public static function getStock(\RKW\RkwOrder\Domain\Model\Publication $publication)
    {
        // count already ordered publications
        $maximumOrderAmount = $publication->getStock();

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        /** @var \RKW\RkwOrder\Domain\Repository\OrderRepository $orderRepository */
        $orderRepository = $objectManager->get('RKW\\RkwOrder\\Domain\\Repository\\OrderRepository');
        $orderForCount = $orderRepository->findByPublication($publication);

        /** @var \RKW\RkwOrder\Domain\Model\Order $order */
        foreach ($orderForCount as $order) {
            $maximumOrderAmount -= $order->getAmount();
        }

        if ($maximumOrderAmount < 0) {
            $maximumOrderAmount = 0;
        }

        return $maximumOrderAmount;
        //===
    }


    /**
     * Returns publications of a series which are not longer available
     *
     * @param \RKW\RkwOrder\Domain\Model\Publication $publication
     * @return array
     */
    public static function getOutOfStockSeries(\RKW\RkwOrder\Domain\Model\Publication $publication)
    {
        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        // get all publications of series
        /** @var \RKW\RkwOrder\Domain\Repository\PublicationRepository $publicationRepository */
        $publicationRepository = $objectManager->get('RKW\\RkwOrder\\Domain\\Repository\\PublicationRepository');
        $publicationSeriesList = $publicationRepository->findBySeries($publication->getSeries());

        // check getRemainingMaxOrderAmount-function
        /** @var \RKW\RkwOrder\Domain\Model\Publication $publication */
        $notAvailablePublicationList = array();
        foreach ($publicationSeriesList as $publicationSeries) {

            // skip the given $publication element (this is asking for other pages of the series)
            if ($publication != $publicationSeries) {
                if (self::getStock($publicationSeries) <= 0) {
                    $notAvailablePublicationList[] = $publicationSeries;
                }
            }
        }

        return $notAvailablePublicationList;
        //===
    }
}