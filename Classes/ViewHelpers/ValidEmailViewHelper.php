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
 * ValidEmailViewHelper
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ValidEmailViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Check if email of Fe-User is valid
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser | null
     * @return boolean
     */
    public function render(\RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser = null)
    {
        if (
            ($frontendUser)
            && ($frontendUser->getUsername())
        ) {

            return \RKW\RkwRegistration\Tools\Registration::validEmail($frontendUser);
            //===
        }

        return false;
        //===
    }

}