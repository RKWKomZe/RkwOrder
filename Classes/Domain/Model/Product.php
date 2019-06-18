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
 * Class Publication
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Product extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * title
     *
     * @var string
     */
    protected $title;

    /**
     * subtitle
     *
     * @var string
     */
    protected $subtitle;


    /**
     * stock
     *
     * @var int
     */
    protected $stock = 0;


    /**
     * orderedExternal
     *
     * @var int
     */
    protected $orderedExternal;


    /**
     * bundleOnly
     *
     * @var boolean
     */
    protected $bundleOnly = false;


    /**
     * subscriptionOnly
     *
     * @var boolean
     */
    protected $subscriptionOnly = false;


    /**
     * page
     *
     * @var \RKW\RkwOrder\Domain\Model\Pages
     */
    protected $page;

    
    /**
     * image
     *
     * @var \RKW\RkwBasics\Domain\Model\FileReference
     */
    protected $image = null;    


    /**
     * productParent
     *
     * @var \RKW\RkwOrder\Domain\Model\Product
     */
    protected $productParent;


    /**
     * backendUser
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwOrder\Domain\Model\BackendUser>
     */
    protected $backendUser;


    /**
     * adminEmail
     *
     * @var string
     */
    protected $adminEmail;



    
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
        $this->backendUser = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the subtitle
     *
     * @return string $subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Sets the subtitle
     *
     * @param string $subtitle
     * @return void
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }


    /**
     * Returns the stock
     *
     * @return int $stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Sets the stock
     *
     * @param int $stock
     * @return void
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * Returns the bundleOnly
     *
     * @return boolean $bundleOnly
     */
    public function getBundleOnly()
    {
        return $this->bundleOnly;
    }

    /**
     * Sets the bundleOnly
     *
     * @param boolean $bundleOnly
     * @return void
     */
    public function setBundleOnly($bundleOnly)
    {
        $this->bundleOnly = $bundleOnly;
    }

    /**
     * Returns the subscriptionOnly
     *
     * @return boolean $subscriptionOnly
     */
    public function getSubscriptionOnly()
    {
        return $this->subscriptionOnly;
    }

    /**
     * Sets the subscriptionOnly
     *
     * @param boolean $subscriptionOnly
     * @return void
     */
    public function setSubscriptionOnly($subscriptionOnly)
    {
        $this->subscriptionOnly = $subscriptionOnly;
    }


    /**
     * Returns the orderedExternal
     *
     * @return int $orderedExternal
     */
    public function getOrderedExternal()
    {
        return $this->orderedExternal;
    }


    /**
     * Sets the orderedExternal
     *
     * @param int $orderedExternal
     * @return void
     */
    public function setOrderedExternal($orderedExternal)
    {
        $this->orderedExternal = $orderedExternal;
    }


    /**
     * Returns the productParent
     *
     * @return \RKW\RkwOrder\Domain\Model\Product $productParent
     */
    public function getProductParent()
    {
        return $this->productParent;
    }

    /**
     * Sets the productParent
     *
     * @param \RKW\RkwOrder\Domain\Model\Product $productParent
     * @return void
     */
    public function setProductParent(\RKW\RkwOrder\Domain\Model\Product $productParent)
    {
        $this->productParent = $productParent;
    }


    /**
     * Returns the page
     *
     * @return \RKW\RkwOrder\Domain\Model\Pages $page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Sets the page
     *
     * @param \RKW\RkwOrder\Domain\Model\Pages $page
     * @return void
     */
    public function setPage(\RKW\RkwOrder\Domain\Model\Pages $page)
    {
        $this->page = $page;
    }


    /**
     * Returns the image
     *
     * @return \RKW\RkwBasics\Domain\Model\FileReference $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param \RKW\RkwBasics\Domain\Model\FileReference $image
     * @return void
     */
    public function setImage(\RKW\RkwBasics\Domain\Model\FileReference $image)
    {
        $this->image = $image;
    }    
    

    /**
     * Adds a backendUser
     *
     * @param \RKW\RkwOrder\Domain\Model\BackendUser $backendUser
     * @return void
     */
    public function addBackendUser(\RKW\RkwOrder\Domain\Model\BackendUser $backendUser)
    {
        $this->backendUser->attach($backendUser);
    }

    /**
     * Removes a backendUser
     *
     * @param \RKW\RkwOrder\Domain\Model\BackendUser $backendUser
     * @return void
     */
    public function removeBackendUser(\RKW\RkwOrder\Domain\Model\BackendUser $backendUser)
    {
        $this->backendUser->detach($backendUser);
    }

    /**
     * Returns the EventWorkshop
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwOrder\Domain\Model\BackendUser> $backendUser
     */
    public function getBackendUser()
    {
        return $this->backendUser;
    }

    /**
     * Sets the EventWorkshop
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\RKW\RkwOrder\Domain\Model\BackendUser> $backendUser
     * @return void
     */
    public function setBackendUser(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $backendUser)
    {
        $this->backendUser = $backendUser;
    }

    /**
     * Returns the adminEmail
     *
     * @return string $adminEmail
     */
    public function getAdminEmail()
    {
        return $this->adminEmail;
    }

    /**
     * Sets the adminEmail
     *
     * @param string $adminEmail
     * @return void
     */
    public function setAdminEmail($adminEmail)
    {
        $this->adminEmail = $adminEmail;
    }

}