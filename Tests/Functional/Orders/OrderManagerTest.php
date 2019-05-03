<?php
namespace RKW\RkwOrder\Tests\Functional\Orders;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;

use RKW\RkwOrder\Orders\OrderManager;
use RKW\RkwOrder\Domain\Model\Order;
use RKW\RkwOrder\Domain\Repository\OrderRepository;
use RKW\RkwRegistration\Domain\Repository\FrontendUserRepository;

use RKW\RkwRegistration\Domain\Repository\BackendUserRepository;
use RKW\RkwMailer\Domain\Repository\StatisticMailRepository;


use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

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
 * OrderManagerTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwMailer
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class OrderManagerTest extends FunctionalTestCase
{

    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/rkw_basics',
        'typo3conf/ext/rkw_registration',
        'typo3conf/ext/rkw_mailer',
        'typo3conf/ext/rkw_order',
    ];

    /**
     * @var string[]
     */
    protected $coreExtensionsToLoad = [];

    /**
     * @var \RKW\RkwOrder\Orders\OrderManager
     */
    private $subject = null;

    /**
     * @var \RKW\RkwOrder\Domain\Repository\FrontendUserRepository
     */
    private $frontendUserRepository;

    /**
     * @var \RKW\RkwOrder\Domain\Repository\OrderRepository
     */
    private $orderRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    private $persistenceManager = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    private $objectManager = null;


    /**
     * Setup
     * @throws \Exception
     */
    protected function setUp()
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/Fixtures/Database/FeUsers.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/Pages.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/Order.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/ShippingAddress.xml');


        /*

        $this->importDataSet(__DIR__ . '/Fixtures/Database/QueueMail.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/QueueRecipient.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/StatisticMail.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/BeUsers.xml');
        */


        $this->setUpFrontendRootPage(
            1,
            [
                'EXT:rkw_registration/Configuration/TypoScript/setup.txt',
                'EXT:rkw_order/Configuration/TypoScript/setup.txt',
                'EXT:rkw_order/Tests/Functional/Service/Fixtures/Frontend/Configuration/Rootpage.typoscript',
            ]
        );

        /*
                $this->setUpFrontendRootPage(
                    2,
                    [
                        'EXT:rkw_registration/Configuration/TypoScript/setup.txt',
                        'EXT:rkw_mailer/Configuration/TypoScript/setup.txt',
                        'EXT:rkw_mailer/Tests/Functional/Service/Fixtures/Frontend/Configuration/Subpage.typoscript',
                    ]
                );*/

        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /*
        $this->queueMailRepository = $this->objectManager->get(QueueMailRepository::class);
        $this->queueRecipientRepository = $this->objectManager->get(QueueRecipientRepository::class);
        $this->statisticMailRepository = $this->objectManager->get(StatisticMailRepository::class);
        $this->backendUserRepository = $this->objectManager->get(BackendUserRepository::class);
        */

        $this->subject = $this->objectManager->get(OrderManager::class);
        $this->frontendUserRepository = $this->objectManager->get(FrontendUserRepository::class);
        $this->orderRepository = $this->objectManager->get(OrderRepository::class);

    }


    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenNoFrontendUserAndTermsFalseThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.acceptTerms');

        /** @var \RKW\Rkworder\Domain\Model\Order $order */
        $order = $this->orderRepository->findByUid(1);

        $this->subject->createOrder($order, null, false, false);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenNoFrontendUserAndTermsTrueAndPrivacyFalseThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.acceptPrivacy');

        /** @var \RKW\Rkworder\Domain\Model\Order $order */
        $order = $this->orderRepository->findByUid(1);

        $this->subject->createOrder($order, null, true, false);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenFrontendUserAndTermsTrueAndPrivacyFalseThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.acceptPrivacy');

        /** @var \RKW\Rkworder\Domain\Model\Order $order */
        $order = GeneralUtility::makeInstance(Order::class);

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser  $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        $this->subject->createOrder($order, $frontendUser, true, false);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenFrontendUserAndTermsFalseAndPrivacyTrueReturnsTrue ()
    {

        /** @var \RKW\Rkworder\Domain\Model\Order $order */
        $order = $this->orderRepository->findByUid(1);

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser  $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        static::assertTrue($this->subject->createOrder($order, $frontendUser, false, true));

    }


    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenFrontendUserWithInvalidEmailAndTermsTrueAndPrivacyTrueThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.invalidEmail');

        /** @var \RKW\Rkworder\Domain\Model\Order $order */
        $order = $this->orderRepository->findByUid(1);
        $order->setEmail('invalid-email');

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser  $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        $this->subject->createOrder($order, $frontendUser, true, true);

    }

    //=============================================


    /**
     * TearDown
     */
    protected function tearDown()
    {
        parent::tearDown();
    }








}