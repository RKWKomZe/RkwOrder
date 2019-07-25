<?php

namespace RKW\RkwOrder\Domain\Model;

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
 * Class ProductBundle
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ProductBundle extends Product
{

     /**
     * allowSingleOrder
     *
     * @var boolean
     */
    protected $allowSingleOrder = true;



    /**
     * Returns the allowSingleOrder
     *
     * @return boolean $bundleOnly
     */
    public function getAllowSingleOrder()
    {
        return $this->allowSingleOrder;
    }

    /**
     * Sets the allowSingleOrder
     *
     * @param boolean $allowSingleOrder
     * @return void
     */
    public function setAllowSingleOrder($allowSingleOrder)
    {
        $this->allowSingleOrder = $allowSingleOrder;
    }



}