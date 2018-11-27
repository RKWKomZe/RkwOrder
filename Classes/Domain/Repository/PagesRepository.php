<?php

namespace RKW\RkwOrder\Domain\Repository;


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
 * Class PagesRepository
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class PagesRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Returns all parent pages that have been imported via bm_pdf2content
     *
     * @api Used by RKW Soap
     * @return array|null|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAllImportedParentPages()
    {

        // Check if extension is installed
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('bm_pdf2content')) {

            $query = $this->createQuery();
            $query->getQuerySettings()->setRespectStoragePage(false);
            $query->getQuerySettings()->setIncludeDeleted(true);
            $query->getQuerySettings()->setIgnoreEnableFields(true);

            $query->matching(
                $query->logicalAnd(
                    $query->equals('tx_bmpdf2content_is_import', 1),
                    $query->equals('tx_bmpdf2content_is_import_sub', 0)
                )
            );

            return $query->execute();
            //===
        }

        return null;
        //===

    }
}