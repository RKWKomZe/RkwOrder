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
 * Class Pages
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Pages extends \RKW\RkwBasics\Domain\Model\Pages
{

    /**
     * txRkworderPublication
     *
     * @var \RKW\RkwOrder\Domain\Model\Publication
     */
    protected $txRkworderPublication = null;

    /**
     * Returns the txRkworderPublication
     *
     * @return \RKW\RkwOrder\Domain\Model\Publication $txRkworderPublication
     */
    public function getTxRkworderPublication()
    {
        return $this->txRkworderPublication;
    }

    /**
     * Sets the txRkworderPublication
     *
     * @param \RKW\RkwOrder\Domain\Model\Publication $txRkworderPublication
     * @return void
     */
    public function setTxRkworderPublication(\RKW\RkwOrder\Domain\Model\Publication $txRkworderPublication)
    {
        $this->txRkworderPublication = $txRkworderPublication;
    }

}