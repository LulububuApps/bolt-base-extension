<?php

declare(strict_types=1);

namespace Lulububu\BaseExtension\Controller;

use Bolt\Controller\Frontend\DetailController;
use Lulububu\BaseExtension\Service\SettingsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageController
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Controller
 */
class HomepageController extends BaseController
{
    /**
     * @Route(
     *     "/",
     *     name="lulububu_homepage",
     *     methods={"GET"},
     * )
     * @Route(
     *     "/{_locale}",
     *     name="lulububu_homepage_locale",
     *     requirements={"_locale": "%app_locales%"},
     *     methods={"GET"},
     * )
     *
     * @param SettingsService $settingsService
     * @param DetailController $detailController
     * @param ErrorController $errorController
     * @return Response
     */
    public function homepage(
        SettingsService $settingsService,
        DetailController $detailController,
        ErrorController $errorController
    ): Response
    {
        $maintenanceMode = $this->isMaintenanceMode();
        $isLoggedIn      = $this->isLoggedIn();

        if ($maintenanceMode && !$isLoggedIn) {
            return $errorController->showMaintenance();
        }

        $settings   = $settingsService->getSettings();
        $homepageId = $settings->getFieldValue('homepage');

        if (!$homepageId || !is_integer($homepageId)) {
            return $errorController->showNoHomepage();
        }

        return $detailController->record($homepageId);
    }
}