<?php

namespace RKW\RkwOrder\Domain\Repository;

use RKW\RkwBasics\Helper\QueryTypo3;

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
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderProductRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{



    /**
     * Get ordered sum of one product
     *
     * @param \RKW\RkwOrder\Domain\Model\Product $product
     * @param bool $preOrder
     * @return int
     * @throws \TYPO3\CMS\Core\Type\Exception\InvalidEnumerationValueException
     */
    public function getOrderedSumByProductAndPreOrder(\RKW\RkwOrder\Domain\Model\Product $product, $preOrder = false)
    {

        $whereAddition = ' AND tx_rkworder_domain_model_orderproduct.is_pre_order = 0';
        if ($preOrder) {
            $whereAddition = ' AND tx_rkworder_domain_model_orderproduct.is_pre_order = 1';
        }

        $query = $this->createQuery();
        $query->statement('
            SELECT SUM(amount) as sum FROM tx_rkworder_domain_model_orderproduct 
            WHERE tx_rkworder_domain_model_orderproduct.product = ' . intval($product->getUid()) .
            $whereAddition .
            QueryTypo3::getWhereClauseForVersioning('tx_rkworder_domain_model_orderproduct') .
            QueryTypo3::getWhereClauseForEnableFields('tx_rkworder_domain_model_orderproduct') . '
        
        ');

        $result = $query->execute(true);
        return intval($result[0]['sum']);
        //====
    }



}