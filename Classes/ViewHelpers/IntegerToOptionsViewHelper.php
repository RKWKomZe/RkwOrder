<?php

namespace RKW\RkwOrder\ViewHelpers;
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
 * IntegerToOptionsViewHelper
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class IntegerToOptionsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Get integer and creates an array
     *
     * @param int $numberOfOptions
     * @param string $append
     * @param string $appendFirst
     * @return array
     */
    public function render($numberOfOptions, $append = '', $appendFirst = '')
    {

        $array = array();

        if (!$appendFirst) {
            $appendFirst = $append;
        }

        for ($i = 1; $i <= $numberOfOptions; $i++) {

            if ($i <= 5) {

                if ($i == 1) {
                    $array[$i] = $i . ' ' . $appendFirst;

                } else {
                    $array[$i] = $i . ' ' . $append;
                }

                // in steps of 5
            } else {
                if (
                    ($i > 5)
                    && ($i <= 20)
                    && ($i % 5 == 0)
                ) {
                    $array[$i] = $i . ' ' . $append;

                    // in steps of 10
                } else {
                    if (
                        ($i > 20)
                        && ($i % 10 == 0)
                    ) {
                        $array[$i] = $i . ' ' . $append;
                    }
                }
            }
        }

        return $array;
        //===

    }

}