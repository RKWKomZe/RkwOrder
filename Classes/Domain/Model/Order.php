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
     * sendSeries
     *
     * @var integer
     */
    protected $sendSeries = 0;


    /**
     * subscribe
     *
     * @var integer
     */
    protected $subscribe = 0;


    /**
     * gender
     *
     * @var integer
     * @validate \RKW\RkwRegistration\Validation\GenderValidator
     */
    protected $gender = 99;


    /**
     * title
     *
     * @var \RKW\RkwRegistration\Domain\Model\Title
     */
    protected $title = null;


    /**
     * firstName
     *
     * @var string
     * @validate NotEmpty, String
     */
    protected $firstName;

    /**
     * lastName
     *
     * @var string
     * @validate NotEmpty, String
     */
    protected $lastName;

    /**
     * company
     *
     * @var string
     */
    protected $company;

    /**
     * fullName
     *
     * @var string
     */
    protected $fullName;

    /**
     * address
     *
     * @var string
     * @validate NotEmpty, String
     */
    protected $address;

    /**
     * zip
     *
     * @var string
     * @validate \RKW\RkwOrder\Validation\Validator\ZipValidator
     */
    protected $zip;

    /**
     * city
     *
     * @var string
     * @validate NotEmpty, String
     */
    protected $city;

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
    protected $remark;

    /**
     * amount
     *
     * @var int
     * @validate \RKW\RkwOrder\Validation\Validator\NotZeroValidator
     */
    protected $amount = 1;

    /**
     * frontendUser
     *
     * @var \RKW\RkwRegistration\Domain\Model\FrontendUser
     */
    protected $frontendUser = null;

    /**
     * pages
     *
     * @var \RKW\RkwOrder\Domain\Model\Pages
     */
    protected $pages = null;

    /**
     * pages
     *
     * @var \RKW\RkwOrder\Domain\Model\Publication
     */
    protected $publication = null;


    /**
     * Returns the crdate value
     *
     * @return integer
     * @api
     */
    public function getCrdate()
    {

        return $this->crdate;
        //===
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
        //===
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
     * Returns the sendSeries
     *
     * @return integer $sendSeries
     */
    public function getSendSeries()
    {
        return $this->sendSeries;
    }

    /**
     * Sets the sendSeries
     *
     * @param boolean $sendSeries
     * @return void
     */
    public function setSendSeries($sendSeries)
    {
        $this->sendSeries = (boolean)$sendSeries;
    }


    /**
     * Returns the Subscribe
     *
     * @return integer $subscribe
     */
    public function getSubscribe()
    {
        return $this->subscribe;
    }

    /**
     * Sets the Subscribe
     *
     * @param boolean $subscribe
     * @return void
     */
    public function setSubscribe($subscribe)
    {
        $this->subscribe = (boolean)$subscribe;
    }


    /**
     * Returns the gender
     *
     * @return integer $gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets the gender
     *
     * @param integer $gender
     * @return void
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Returns the title
     *
     * @return \RKW\RkwRegistration\Domain\Model\Title $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * Hint: default "null" is needed to make value in forms optional
     *
     * @param \RKW\RkwRegistration\Domain\Model\Title $title
     * @return void
     */
    public function setTitle(\RKW\RkwRegistration\Domain\Model\Title $title = null)
    {
        $this->title = $title;
    }

    /**
     * Returns the firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets the firstName
     *
     * @param string $firstName
     * @return void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Returns the lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets the lastName
     *
     * @param string $lastName
     * @return void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }


    /**
     * Returns the company
     *
     * @return string $company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Sets the company
     *
     * @param string $company
     * @return void
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }


    /**
     * ### Additional getter without database support ###
     * Returns the fullName
     *
     * @return string $fullName
     */
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the zip
     *
     * @return string $zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     *
     * @param string $zip
     * @return void
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Returns the city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city
     *
     * @param string $city
     * @return void
     */
    public function setCity($city)
    {
        $this->city = $city;
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
     * Returns the amount
     *
     * @return int $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets the amount
     *
     * @param int $amount
     * @return void
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     * @return \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
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
     * Returns the pages
     *
     * @return \RKW\RkwOrder\Domain\Model\Pages $pages
     */
    public function getPages()
    {
        return $this->pages;
    }


    /**
     * Returns the publication
     *
     * @return \RKW\RkwOrder\Domain\Model\Publication $publication
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Sets the publication
     *
     * @param \RKW\RkwOrder\Domain\Model\Publication $publication
     * @return void
     */
    public function setPublication(\RKW\RkwOrder\Domain\Model\Publication $publication)
    {
        $this->publication = $publication;
    }
}