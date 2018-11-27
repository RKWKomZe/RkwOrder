<?php
namespace RKW\RkwOrder\Controller;

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
 * OrderCommandController
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController
{
	/**
	 * objectManager
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager;

    /**
     * objectManager
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * orderRepository
     *
     * @var \RKW\RkwOrder\Domain\Repository\OrderRepository
     * @inject
     */
    protected $orderRepository;

	/**
	 * @var \TYPO3\CMS\Core\Log\Logger
	 */
	protected $logger;

	/**
	 * Initialize the controller.
	 */
	protected function initializeController() {

		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
	}



    /**
     * Cleanup for anonymousToken and anonymousUser
     * !! DANGER !! Cleanup executes a real MySQL-Delete- Query!!!
     *
     * @param integer $hoursFromNow Defines which datasets (in days from now) should be deleted (send date is reference)
     * @return void
     */
    public function cleanupCommand($hoursFromNow = 8760) {

        if ($cleanupTimestamp = time() - intval($hoursFromNow) * 60 * 60) {

            if ($queueOrder = $this->orderRepository->findAllOldOrder($cleanupTimestamp)) {

                // delete corresponding data and the mail itself
                foreach ($queueOrder as $order) {

					// 3. Delete order
					$this->orderRepository->deleteBySelf($order);

                }

                $this->getLogger()->log(\TYPO3\CMS\Core\Log\LogLevel::INFO, 'Successfully cleaned up database.');
            }
        }
    }



	/**
	 * Returns logger instance
	 *
	 * @return \TYPO3\CMS\Core\Log\Logger
	 */
	protected function getLogger() {

		if (! $this->logger instanceof \TYPO3\CMS\Core\Log\Logger)
			$this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__);

		return $this->logger;
		//===
	}

}