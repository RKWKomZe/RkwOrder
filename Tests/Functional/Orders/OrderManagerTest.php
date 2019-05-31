<?php
namespace RKW\RkwOrder\Tests\Functional\Orders;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;

use RKW\RkwOrder\Domain\Model\Order;
use RKW\RkwOrder\Domain\Model\ShippingAddress;
use RKW\RkwOrder\Orders\OrderManager;
use RKW\RkwOrder\Domain\Repository\OrderRepository;
use RKW\RkwOrder\Domain\Repository\PublicationRepository;
use RKW\RkwOrder\Domain\Repository\ShippingAddressRepository;

use RKW\RkwRegistration\Domain\Model\FrontendUser;
use RKW\RkwRegistration\Domain\Repository\FrontendUserRepository;

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
     * @var \RKW\RkwOrder\Domain\Model\Order
     */
    private $fixtureDummy = null;

    /**
     * @var \RKW\RkwOrder\Orders\OrderManager
     */
    private $subject = null;

    /**
     * @var \RKW\RkwOrder\Domain\Repository\FrontendUserRepository
     */
    private $frontendUserRepository;

    /**
     * @var \RKW\RkwOrder\Domain\Repository\ShippingAddressRepository
     */
    private $shippingAddressRepository;


    /**
     * @var \RKW\RkwOrder\Domain\Repository\OrderRepository
     */
    private $orderRepository;

    /**
     * @var \RKW\RkwOrder\Domain\Repository\PublicationRepository
     */
    private $publicationRepository;

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

        $this->importDataSet(__DIR__ . '/Fixtures/Database/BeUsers.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/FeUsers.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/Pages.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/Publication.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/Order.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/ShippingAddress.xml');



        $this->setUpFrontendRootPage(
            1,
            [
                'EXT:rkw_registration/Configuration/TypoScript/setup.txt',
                'EXT:rkw_order/Configuration/TypoScript/setup.txt',
                'EXT:rkw_order/Tests/Functional/Service/Fixtures/Frontend/Configuration/Rootpage.typoscript',
            ]
        );


        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        $this->subject = $this->objectManager->get(OrderManager::class);
        $this->frontendUserRepository = $this->objectManager->get(FrontendUserRepository::class);
        $this->shippingAddressRepository = $this->objectManager->get(ShippingAddressRepository::class);
        $this->orderRepository = $this->objectManager->get(OrderRepository::class);
        $this->publicationRepository = $this->objectManager->get(PublicationRepository::class);

        /** @var \RKW\RkwOrder\Domain\Model\ShippingAddress $shippingAddress */
        $shippingAddress = $this->shippingAddressRepository->findByUid(1);

        /** @var \RKW\RkwOrder\Domain\Model\Publication $publication */
        $publication = $this->publicationRepository->findByUid(1);

        $this->fixtureDummy = GeneralUtility::makeInstance(Order::class);
        $this->fixtureDummy->setPublication($publication);
        $this->fixtureDummy->setShippingAddress($shippingAddress);
        $this->fixtureDummy->setEmail('email@rkw.de');

    }


    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenNoFrontendUserAndTermsFalseThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.acceptTerms');

        $this->subject->createOrder($this->fixtureDummy, null, false, false);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenNonPersistentFrontendUserAndTermsFalseThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.acceptTerms');

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser */
        $frontendUser = GeneralUtility::makeInstance(FrontendUser::class);

        $this->subject->createOrder($this->fixtureDummy, $frontendUser, false, false);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenNoFrontendUserAndTermsTrueAndPrivacyFalseThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.acceptPrivacy');

        $this->subject->createOrder($this->fixtureDummy, null, true, false);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenFrontendUserAndTermsTrueAndPrivacyFalseThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.acceptPrivacy');

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser  $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        $this->subject->createOrder($this->fixtureDummy, $frontendUser, true, false);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenNonPersistentFrontendUserAndTermsFalseAndPrivacyTrueThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.acceptTerms');

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser */
        $frontendUser = GeneralUtility::makeInstance(FrontendUser::class);

        $this->subject->createOrder($this->fixtureDummy, $frontendUser, false, true);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenFrontendUserAndTermsFalseAndPrivacyTrueReturnsTrue ()
    {

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser  $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        static::assertTrue($this->subject->createOrder($this->fixtureDummy, $frontendUser, false, true));

    }


    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenFrontendUserWithInvalidEmailAndTermsTrueAndPrivacyTrueThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.invalidEmail');


        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser  $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        $this->fixtureDummy->setEmail('invalid-email');

        $this->subject->createOrder($this->fixtureDummy, $frontendUser, true, true);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     */
    public function createOrderGivenFrontendUserWithInvalidEmailAndTermsTrueAndPrivacyTrueAndMissingShippingAddressModelThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.noShippingAddress');

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser  $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        /** @var \RKW\RkwOrder\Domain\Model\Order $order */
        $order = GeneralUtility::makeInstance(Order::class);
        $order->setEmail('email@rkw.de');

        $this->subject->createOrder($order, $frontendUser, true, true);

    }

    //=============================================

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function saveOrderGivenOrderWithoutShippingAddressModelThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.noShippingAddress');

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        /** @var \RKW\RkwOrder\Domain\Model\Order $order */
        $order = GeneralUtility::makeInstance(Order::class);

        $this->subject->saveOrder($order, $frontendUser);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function saveOrderGivenOrderAndNonPersistedFrontendUserThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.frontendUserNotPersisted');

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser */
        $frontendUser = GeneralUtility::makeInstance(FrontendUser::class);

        $this->subject->saveOrder($this->fixtureDummy, $frontendUser);

    }

    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function saveOrderGivenPersistedOrderAndPersistedFrontendUserThrowsException ()
    {

        static::expectException(\RKW\RkwOrder\Exception::class);
        static::expectExceptionMessage('orderManager.error.orderAlreadyPersisted');

        /** @var \RKW\RkwOrder\Domain\Model\Order $order */
        $order = $this->orderRepository->findByUid(1);

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        $this->subject->saveOrder($order, $frontendUser);

    }


    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function saveOrderOrderAndPersistedFrontendUserReturnsTrueAndAddsOrderAndShippingAddressToDatabaseWithFrontendUserSet ()
    {

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        self::assertTrue($this->subject->saveOrder($this->fixtureDummy, $frontendUser));

        /** @var \RKW\RkwOrder\Domain\Model\Order $order */
        $order = $this->orderRepository->findByUid(2);

        self::assertEquals($frontendUser->getUid(), $order->getFrontendUser()->getUid());
        self::assertEquals($frontendUser->getUid(), $order->getShippingAddress()->getFrontendUser()->getUid());

    }


    /**
     * @test
     * @throws \RKW\RkwOrder\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function saveOrderOrderAndPersistedFrontendUserReturnsTrueAndAddsOrderAndShippingAddressWithExpectedDataToDatabase ()
    {

        /** @var \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser */
        $frontendUser = $this->frontendUserRepository->findByUid(1);

        self::assertTrue($this->subject->saveOrder($this->fixtureDummy, $frontendUser));

        /** @var \RKW\RkwOrder\Domain\Model\Order $order */
        $order = $this->orderRepository->findByUid(2);

/** ToDO: Check for values of object!!!! */

        self::assertEquals($frontendUser->getUid(), $order->getFrontendUser()->getUid());
        self::assertEquals($frontendUser->getUid(), $order->getShippingAddress()->getFrontendUser()->getUid());

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