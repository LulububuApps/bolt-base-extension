<?php

namespace Lulububu\BaseExtension;

use Appolo\BoltSeo\Widget\LulububuInjectorWidget;
use Bolt\Entity\Content;
use Bolt\Extension\BaseExtension as BoltBaseExtension;
use Bolt\Repository\ContentRepository;
use Lulububu\BaseExtension\Listener\SettingsListener;
use Lulububu\BaseExtension\Service\SettingsService;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
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

        $this->addWidget(new LulububuInjectorWidget());
        $this->getTwig()->addGlobal('settings', $settings);
        $this->addListener('kernel.request', [new SettingsListener($settingsService), 'kernelRequestEvent']);
    }

    /**
     *
     */
    public function install(): void
    {
        /**
         * @var Container $container
         */
        $container   = $this->getContainer();
        $projectDir  = $container->getParameter('kernel.project_dir');
        $public      = $container->getParameter('bolt.public_folder');
        $filesystem  = new Filesystem();
        $source      = \dirname(__DIR__) . '/assets/';
        $destination = $projectDir . '/' . $public . '/assets/';

        $filesystem->mirror($source, $destination);
    }
}