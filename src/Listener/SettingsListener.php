<?php

namespace Lulububu\BaseExtension\Listener;

use Bolt\Widget\Injector\RequestZone;
use Lulububu\BaseExtension\Service\SettingsService;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class SettingsListener
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Listener
 */
class SettingsListener
{
    /**
     * @var SettingsService $settingsService
     */
    protected $settingsService;

    /**
     * SettingsListener constructor.
     *
     * @param SettingsService $settingsService
     */
    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @param RequestEvent $event
     * @throws \Exception
     */
    public function kernelRequestEvent(RequestEvent $event): void
    {
        if (RequestZone::isForFrontend($event->getRequest())) {
            return;
        }

        $settings = $this->settingsService->getSettings();

        if (!$settings) {
            throw new \Exception('Save settings once in the backend.');
        }
    }
}