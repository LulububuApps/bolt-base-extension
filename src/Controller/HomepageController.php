<?php

namespace Lulububu\BaseExtension\Controller;

use Bolt\Controller\Frontend\DetailController;
use Lulububu\BaseExtension\Service\ErrorService;
use Lulububu\BaseExtension\Service\SettingsService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomepageController
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Controller
 */
class HomepageController extends BaseController
{
    /**
     * @param SettingsService $settingsService
     * @param DetailController $detailController
     * @param ErrorService $errorService
     * @return Response
     */
    public function homepage(
        SettingsService $settingsService,
        DetailController $detailController,
        ErrorService $errorService
    ): Response
    {
        $maintenanceMode = $this->isMaintenanceMode();
        $isLoggedIn      = $this->isLoggedIn();

        if ($maintenanceMode && !$isLoggedIn) {
            return $errorService->showMaintenance();
        }

        $settings   = $settingsService->getSettings();
        $homepageId = $settings->getFieldValue('homepage');

        if (!$homepageId || !is_integer($homepageId)) {
            return $errorService->showNoHomepage();
        }

        return $detailController->record($homepageId);
    }
}