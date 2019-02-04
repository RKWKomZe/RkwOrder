<?php

namespace RKW\RkwOrder\ViewHelpers;

use \RKW\RkwOrder\Helper\Stock;

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
 * OutOfStockSeriesViewHelper
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright RKW Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OutOfStockSeriesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Check if all parts of a series is still available
     *
     * @param \RKW\RkwOrder\Domain\Model\Publication $publication
     * @return array
     */
    public function render(\RKW\RkwOrder\Domain\Model\Publication $publication)
    {
        return Stock::getOutOfStockSeries($publication);
        //===
    }
}