<?php

namespace RKW\RkwOrder\Domain\Repository;

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
 * Class ProductRepository
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ProductRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * initializeObject
     *
     * @return void
     */
    public function initializeObject()
    {

        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');

        // don't add the pid constraint
        $querySettings->setRespectStoragePage(false);

        $this->setDefaultQuerySettings($querySettings);
    }



    /**
     * Find all products by a list of uids
     *
     * @param string $uidList
     * @return array
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findByUidList($uidList)
    {

        $query = $this->createQuery();
        $uidArray = explode(',', $uidList);
        $result = [];

        // 1. Get all products by uid
        $constraints = [];
        foreach ($uidArray as $key => $value) {
            $constraints[] =  $query->equals('uid', $value);
        }

        // we have to keep the order given by the comma-list
        $query->setOrderings($this->orderByKey('uid', $uidArray));

        $products = $query->matching(
            $query->logicalOr(
                $constraints
            )
        )->execute();


        // 2. Check for parentProduct and its settings
        $uidList = [];

        /** @var \RKW\RkwOrder\Domain\Model\Product $product */
        foreach ($products as $product) {

            // check for bundleOnly = true
            // --> add parentProduct instead of given product in some cases
            if (
                ($product->getProductParent())
                && ($product->getProductParent()->getBundleOnly())
                && ($parentId = $product->getProductParent()->getUid())
            ) {

                if (! in_array($parentId, $uidList)) {

                    $query = $this->createQuery();
                    $query->matching(
                        $query->equals('uid', $parentId)
                    );

                    $result[] = $query->execute()->getFirst();
                    $uidList[] = $parentId;
                }

            } else if (! in_array($product->getUid(), $uidList)) {

                // check for subscriptionOnly = true
                // --> add parentProduct as additional product
                if (
                    ($product->getProductParent())
                    && ($parentId = $product->getProductParent()->getUid())
                    && (! in_array($parentId, $uidList))
                ) {

                    $query = $this->createQuery();
                    $query->matching(
                        $query->equals('uid', $parentId)
                    );
                    $result[] = $query->execute()->getFirst();
                    $uidList[] = $parentId;
                }

                $result[] = $product;
                $uidList[] = $product->getUid();
            }
        }

        return $result;
        //===
    }


    /**
     * @param $key
     * @param array $uidArray
     * @return array
     */
    protected function orderByKey($key, $uidArray)
    {
        $order = array();
        foreach ($uidArray as $uid) {
            $order["$key={$uid}"] = \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING;
        }
        return $order;
        //===
    }


}