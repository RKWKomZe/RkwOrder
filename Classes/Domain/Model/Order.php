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
 * Class Order
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Order extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var integer
     */
    protected $crdate;


    /**
     * @var integer
     */
    protected $tstamp;


    /**
     * @var integer
     */
    protected $hidden;


    /**
     * @var integer
     */
    protected $deleted;


    /**
     * Status
     *
     * @var integer
     */
    protected $status = 0;

    /**
     * email
     *
     * @var string
     * @validate EmailAddress, NotEmpty
     */
    protected $email;

    /**
     * remark
     *
     * @var string
     */
    protected $remark = '';


    /**
     * frontendUser
     *
     * @var \RKW\RkwOrder\Domain\Model\FrontendUser
     */
    protected $frontendUser = null;


    /**
     * shippingAddress
     *
     * @var \RKW\RkwOrder\Domain\Model\ShippingAddress
     */
    protected $shippingAddress = null;

    
    /**
     * orderProduct
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwOrder\Domain\Model\OrderProduct>
     * @cascade remove
     */
    protected $orderProduct = null;


    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->orderProduct = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }


    /**
     * Returns the crdate value
     *
     * @return integer
     * @api
     */
    public function getCrdate()
    {
        return $this->crdate;
    }


    /**
     * Returns the tstamp value
     *
     * @return integer
     * @api
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Sets the hidden value
     *
     * @param integer $hidden
     * @api
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }


    /**
     * Returns the hidden value
     *
     * @return integer
     * @api
     */
    public function getHidden()
    {
        return $this->hidden;
        //===
    }

    /**
     * Sets the deleted value
     *
     * @param integer $deleted
     * @api
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }


    /**
     * Returns the deleted value
     *
     * @return integer
     * @api
     */
    public function getDeleted()
    {
        return $this->deleted;
        //===
    }


    /**
     * Sets the status value
     *
     * @param integer $status
     * @api
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    /**
     * Returns the status value
     *
     * @return integer
     * @api
     */
    public function getStatus()
    {
        return $this->status;
        //===
    }

    
    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

  

    /**
     * Returns the remark
     *
     * @return string $remark
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Sets the remark
     *
     * @param string $remark
     * @return void
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * Returns the frontendUser
     *
     * @return \RKW\RkwOrder\Domain\Model\FrontendUser $frontendUser
     */
    public function getFrontendUser()
    {
        return $this->frontendUser;
    }

    /**
     * Sets the frontendUser
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @return void
     */
    public function setFrontendUser(\RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser)
    {
        $this->frontendUser = $frontendUser;
    }

    /**
     * Returns the frontendUser
     *
     * @return \RKW\RkwOrder\Domain\Model\ShippingAddress $shippingAddress
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Sets the frontendUser
     *
     * @param \RKW\RkwOrder\Domain\Model\ShippingAddress $shippingAddress
     * @return void
     */
    public function setShippingAddress(\RKW\RkwOrder\Domain\Model\ShippingAddress $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }


    /**
     * Adds a orderProduct
     *
     * @param \RKW\RkwOrder\Domain\Model\OrderProduct $orderProduct
     * @return void
     */
    public function addOrderProduct(\RKW\RkwOrder\Domain\Model\OrderProduct $orderProduct)
    {
        $this->orderProduct->attach($orderProduct);
    }

    /**
     * Removes a orderProduct
     *
     * @param \RKW\RkwOrder\Domain\Model\OrderProduct $orderProduct
     * @return void
     */
    public function removeOrderProduct(\RKW\RkwOrder\Domain\Model\OrderProduct $orderProduct)
    {
        $this->orderProduct->detach($orderProduct);
    }

    /**
     * Returns the orderProduct
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwOrder\Domain\Model\OrderProduct> $orderProduct
     */
    public function getOrderProduct()
    {
        return $this->orderProduct;
    }

    /**
     * Sets the orderProduct
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwOrder\Domain\Model\OrderProduct> $orderProduct
     * @return void
     */
    public function setOrderProduct(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $orderProduct)
    {
        $this->orderProduct = $orderProduct;
    }
}