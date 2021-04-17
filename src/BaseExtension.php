<?php

namespace Lulububu\BaseExtension;

use Bolt\Entity\Content;
use Bolt\Extension\BaseExtension as BoltBaseExtension;
use Bolt\Repository\ContentRepository;
use Lulububu\BaseExtension\Listener\SettingsListener;
use Lulububu\BaseExtension\Service\SettingsService;
use Symfony\Component\Routing\Route;

/**
 * Class BaseExtension
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension
 */
class BaseExtension extends BoltBaseExtension
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Lulububu Base Extension';
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        $routes    = [];
        $callbacks = $this->getBetterRoutes();

        foreach ($callbacks as $callback => $paths) {
            foreach ($paths as $name => $path) {
                $routes[$name] = new Route(
                    $path,
                    [
                        '_controller' => $callback,
                    ]
                );
            }
        }

        return $routes;
    }

    /**
     * @return array
     */
    protected function getBetterRoutes(): array
    {
        return [];
    }

    /**
     * @param false $cli
     */
    public function initialize($cli = false): void
    {
        /**
         * @var ContentRepository $contentRepository
         */
        $contentRepository = $this->entityManager->getRepository(Content::class);
        $settingsService   = new SettingsService($contentRepository);
        $settings          = $settingsService->getSettings();

        $this->getTwig()->addGlobal('settings', $settings);
        $this->addListener('kernel.request', [new SettingsListener($settingsService), 'kernelRequestEvent']);
    }
}