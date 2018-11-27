<?php

namespace RKW\RkwOrder\Service;

use \RKW\RkwBasics\Helper\Common;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

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
 * RkwMailService
 *
 * @author Maximilian Fäßler <maximilian@faesslerweb.de>
 * @author Steffen Kroggel <developer@steffenkroggel.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwOrder
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RkwMailService implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Handles opt-in event
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwRegistration\Domain\Model\Registration $registration
     * @param mixed $signalInformation
     * @return void
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function handleOptInRequestEvent(\RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser, \RKW\RkwRegistration\Domain\Model\Registration $registration = null)
    {

        // get settings
        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();

        if ($frontendUser->getEmail()) {
            if ($settings['view']['templateRootPaths'][0]) {

                /** @var \RKW\RkwMailer\Service\MailService $mailService */
                $mailService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwMailer\\Service\\MailService');

                // send new user an email with token
                $mailService->setTo($frontendUser, array(
                    'marker' => array(
                        'tokenYes'     => $registration->getTokenYes(),
                        'tokenNo'      => $registration->getTokenNo(),
                        'userSha1'     => $registration->getUserSha1(),
                        'frontendUser' => $frontendUser,
                        'registration' => $registration,
                        'pageUid'      => intval($GLOBALS['TSFE']->id),
                        'loginPid'     => intval($settingsDefault['loginPid']),
                    ),
                ));

                $mailService->getQueueMail()->setSubject(
                    \RKW\RkwMailer\Helper\FrontendLocalization::translate(
                        'rkwMailService.optInRequestEvent.subject',
                        'rkw_order',
                        null,
                        $frontendUser->getTxRkwregistrationLanguageKey()
                    )
                );
                $mailService->getQueueMail()->setPlaintextTemplate($settings['view']['templateRootPaths'][0] . 'Email/OptInRequest');
                $mailService->getQueueMail()->setHtmlTemplate($settings['view']['templateRootPaths'][0] . 'Email/OptInRequest');
                $mailService->getQueueMail()->setPartialPath($settings['view']['partialRootPaths'][0]);

                $mailService->send();
            }
        }
    }


    /**
     * Handles confirm order mail for user
     * Works with RkwRegistration-FrontendUser -> this is correct! (data comes from TxRkwRegistration)
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @return void
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function confirmationOrderUser(\RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser, \RKW\RkwOrder\Domain\Model\Order $order)
    {
        $this->userMail($frontendUser, $order, 'confirmation');
    }


    /**
     * Handles confirm order mail for admin
     *
     * @param \RKW\RkwOrder\Domain\Model\BackendUser|array $backendUser
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @return void
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function confirmationOrderAdmin($backendUser, \RKW\RkwOrder\Domain\Model\Order $order)
    {
        $this->adminMail($backendUser, $order, 'confirmation');
    }


    /**
     * Handles delete order mail for user
     * Works with RkwRegistration-FrontendUser -> this is correct! (data comes from TxRkwRegistration)
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @return void
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function deleteOrderUser(\RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser, \RKW\RkwOrder\Domain\Model\Order $order)
    {
        $this->userMail($frontendUser, $order, 'delete');
    }


    /**
     * Handles delete order mail for admin
     *
     * @param \RKW\RkwOrder\Domain\Model\BackendUser|array $backendUser
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @return void
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function deleteOrderAdmin($backendUser, \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser, \RKW\RkwOrder\Domain\Model\Order $order)
    {
        $this->adminMail($backendUser, $order, 'delete', $frontendUser);
    }


    /**
     * Sends an E-Mail to a Frontend-User
     *
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @param string $action
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function userMail(\RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser, \RKW\RkwOrder\Domain\Model\Order $order, $action = 'confirmation')
    {

        // get settings
        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();

        if ($frontendUser->getEmail()) {
            if ($settings['view']['templateRootPaths'][0]) {

                /** @var \RKW\RkwMailer\Service\MailService $mailService */
                $mailService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwMailer\\Service\\MailService');

                // send new user an email with token
                $mailService->setTo($frontendUser, array(
                    'marker' => array(
                        'order'        => $order,
                        'frontendUser' => $frontendUser,
                        'pageUid'      => intval($GLOBALS['TSFE']->id),
                        'loginPid'     => intval($settingsDefault['loginPid']),
                    ),
                ));

                $mailService->getQueueMail()->setSubject(
                    \RKW\RkwMailer\Helper\FrontendLocalization::translate(
                        'rkwMailService.' . strtolower($action) . 'User.subject',
                        'rkw_order',
                        null,
                        $frontendUser->getTxRkwregistrationLanguageKey()
                    )
                );
                $mailService->getQueueMail()->setPlaintextTemplate($settings['view']['templateRootPaths'][0] . 'Email/' . ucFirst(strtolower($action)) . 'OrderUser');
                $mailService->getQueueMail()->setHtmlTemplate($settings['view']['templateRootPaths'][0] . 'Email/' . ucFirst(strtolower($action)) . 'OrderUser');
                $mailService->getQueueMail()->setPartialPath($settings['view']['partialRootPaths'][0]);

                $mailService->send();
            }
        }

    }


    /**
     * Sends an E-Mail to an Admin
     *
     * @param \RKW\RkwOrder\Domain\Model\BackendUser|array $backendUser
     * @param \RKW\RkwOrder\Domain\Model\Order $order
     * @param string $action
     * @param \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser
     * @throws \RKW\RkwMailer\Service\MailException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function adminMail($backendUser, \RKW\RkwOrder\Domain\Model\Order $order, $action = 'confirmation', \RKW\RkwRegistration\Domain\Model\FrontendUser $frontendUser = null)
    {

        // get settings
        $settings = $this->getSettings(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $settingsDefault = $this->getSettings();

        $recipients = array();
        if (is_array($backendUser)) {
            $recipients = $backendUser;
        } else {
            $recipients[] = $backendUser;
        }

        if ($settings['view']['templateRootPaths'][0]) {

            /** @var \RKW\RkwMailer\Service\MailService $mailService */
            $mailService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('RKW\\RkwMailer\\Service\\MailService');

            foreach ($recipients as $recipient) {

                if (
                    ($recipient instanceof \RKW\RkwOrder\Domain\Model\BackendUser)
                    && ($recipient->getEmail())
                ) {
                    // send new user an email with token
                    $mailService->setTo($recipient, array(
                        'marker'  => array(
                            'order'        => $order,
                            'backendUser'  => $recipient,
                            'frontendUser' => $frontendUser,
                            'pageUid'      => intval($GLOBALS['TSFE']->id),
                            'loginPid'     => intval($settingsDefault['loginPid']),
                        ),
                        'subject' => \RKW\RkwMailer\Helper\FrontendLocalization::translate(
                            'rkwMailService.' . strtolower($action) . 'Admin.subject',
                            'rkw_order',
                            null,
                            $recipient->getLang()
                        ),
                    ));
                }
            }

            if (
                ($order->getFrontendUser())
                && ($order->getFrontendUser()->getEmail())
            ) {
                $mailService->getQueueMail()->setReplyAddress($order->getFrontendUser()->getEmail());
            }

            $mailService->getQueueMail()->setSubject(
                \RKW\RkwMailer\Helper\FrontendLocalization::translate(
                    'rkwMailService.' . strtolower($action) . 'Admin.subject',
                    'rkw_order',
                    null,
                    'de'
                )
            );
            $mailService->getQueueMail()->setPlaintextTemplate($settings['view']['templateRootPaths'][0] . 'Email/' . ucfirst(strtolower($action)) . 'OrderAdmin');
            $mailService->getQueueMail()->setHtmlTemplate($settings['view']['templateRootPaths'][0] . 'Email/' . ucfirst(strtolower($action)) . 'OrderAdmin');
            $mailService->getQueueMail()->setPartialPath($settings['view']['partialRootPaths'][0]);

            if (count($mailService->getTo())) {
                $mailService->send();
            }
        }
    }


    /**
     * Returns TYPO3 settings
     *
     * @param string $which Which type of settings will be loaded
     * @return array
     */
    protected function getSettings($which = ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS)
    {

        return Common::getTyposcriptConfiguration('Rkworder', $which);
        //===
    }
}
