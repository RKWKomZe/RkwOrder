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
            && (! $frontendUser)
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




        return true;
        //===


    }





}