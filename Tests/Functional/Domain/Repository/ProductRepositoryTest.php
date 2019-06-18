<?php
namespace RKW\RkwOrder\Tests\Functional\Domain\Repository;


use Nimut\TestingFramework\TestCase\FunctionalTestCase;

use RKW\RkwOrder\Domain\Repository\OrderRepository;
use RKW\RkwOrder\Domain\Repository\ProductRepository;
use RKW\RkwOrder\Domain\Repository\FrontendUserRepository;

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
 * ProductRepositoryTest
 *
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwMailer
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ProductRepositoryTest extends FunctionalTestCase
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
     * @var \RKW\RkwOrder\Domain\Repository\ProductRepository
     */
    private $subject = null;

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
        $this->importDataSet(__DIR__ . '/Fixtures/Database/ProductRepository/Pages.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/Database/ProductRepository/Product.xml');


        $this->setUpFrontendRootPage(
            1,
            [
                'EXT:rkw_mailer/Configuration/TypoScript/setup.txt',
                'EXT:rkw_registration/Configuration/TypoScript/setup.txt',
                'EXT:rkw_order/Configuration/TypoScript/setup.txt',
                'EXT:rkw_order/Tests/Functional/Domain/Repository/Fixtures/Frontend/Configuration/Rootpage.typoscript',
            ]
        );
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);

        /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->subject = $this->objectManager->get(ProductRepository::class);


    }


    /**
     * @test
     */
    public function findByUidListReturnsListOfProductsAndRespectsEnableFields()
    {

        $result = $this->subject->findByUidList('1,2,3,4');
        static::assertEquals(2, count($result));
        self::assertEquals('1', $result[0]->getUid());
        self::assertEquals('2', $result[1]->getUid());

    }

    /**
     * @test
     */
    public function findByUidListReturnsListOfProductsAndIgnoresDuplicates()
    {

        $result = $this->subject->findByUidList('1,2,3,4,1,2');
        static::assertEquals(2, count($result));
        self::assertEquals('1', $result[0]->getUid());
        self::assertEquals('2', $result[1]->getUid());

    }

    /**
     * @test
     */
    public function findByUidListReturnsListOfProductsAndKeepsGivenOrder()
    {

        $result = $this->subject->findByUidList('4,3,2,1');
        static::assertEquals(2, count($result));
        self::assertEquals('2', $result[0]->getUid());
        self::assertEquals('1', $result[1]->getUid());

    }

    /**
     * @test
     */
    public function findByUidListGivenProductHavingParentProductAndBundleOnlyFalseReturnsListOfProductsIncludingProductHavingParent ()
    {

        $result = $this->subject->findByUidList('1,5,6');
        static::assertEquals(3, count($result));
        self::assertEquals('1', $result[0]->getUid());
        self::assertEquals('5', $result[1]->getUid());
        self::assertEquals('6', $result[2]->getUid());

    }

    /**
     * @test
     */
    public function findByUidListGivenProductHavingParentProductAndBundleOnlyTrueReturnsListOfProductsWithoutProductHavingParentButWithParent ()
    {

        $result = $this->subject->findByUidList('1,5,8');
        static::assertEquals(3, count($result));
        self::assertEquals('1', $result[0]->getUid());
        self::assertEquals('5', $result[1]->getUid());
        self::assertEquals('7', $result[2]->getUid());

    }

    /**
     * @test
     */
    public function findByUidListGivenProductHavingParentProductAndBundleOnlyTrueReturnsListOfProductsWithoutProductHavingParentButWithParentAndIgnoresDuplicates ()
    {

        $result = $this->subject->findByUidList('1,5,8,9,5,1');
        static::assertEquals(3, count($result));
        self::assertEquals('1', $result[0]->getUid());
        self::assertEquals('5', $result[1]->getUid());
        self::assertEquals('7', $result[2]->getUid());

    }

    /**
     * @test
     */
    public function findByUidListGivenProductHavingParentProductReturnsListOfProductsIncludingProductHavingParentAndParent ()
    {

        $result = $this->subject->findByUidList('1,5,11');
        static::assertEquals(4, count($result));
        self::assertEquals('1', $result[0]->getUid());
        self::assertEquals('5', $result[1]->getUid());
        self::assertEquals('10', $result[2]->getUid());
        self::assertEquals('11', $result[3]->getUid());

    }


    /**
     * @test
     */
    public function findByUidListGivenProductHavingParentProductReturnsListOfProductsIncludingProductHavingParentAndParentAndIgnoresDuplicates ()
    {

        $result = $this->subject->findByUidList('1,10,5,11,12');

        static::assertEquals(5, count($result));
        self::assertEquals('1', $result[0]->getUid());
        self::assertEquals('10', $result[1]->getUid());
        self::assertEquals('5', $result[2]->getUid());
        self::assertEquals('11', $result[3]->getUid());
        self::assertEquals('12', $result[4]->getUid());


    }

    /**
     * TearDown
     */
    protected function tearDown()
    {
        parent::tearDown();
    }
}