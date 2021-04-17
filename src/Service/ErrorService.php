<?php

namespace Lulububu\BaseExtension\Service;

use Bolt\Configuration\Config;
use Bolt\Controller\Frontend\DetailController;
use Bolt\Controller\Frontend\TemplateController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Error\LoaderError;

/**
 * Class ErrorService
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Service
 */
class ErrorService
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var DetailController $detailController
     */
    protected $detailController;

    /**
     * @var TemplateController $templateController
     */
    protected $templateController;

    /**
     * BaseController constructor.
     *
     * @param Config $config
     * @param DetailController $detailController
     * @param TemplateController $templateController
     */
    public function __construct(
        Config $config,
        DetailController $detailController,
        TemplateController $templateController
    )
    {
        $this->config             = $config;
        $this->detailController   = $detailController;
        $this->templateController = $templateController;
    }

    /**
     * @return Response
     *
     * @see Method Copied from Bolt\Controller\ErrorController::showMaintenance
     */
    public function showMaintenance(): Response
    {
        foreach ($this->config->get('general/maintenance') as $item) {
            $output = $this->attemptToRender($item);

            if ($output instanceof Response) {
                return $output;
            }
        }

        return new Response('503: Maintenance mode (and there was no proper page configured to display)', Response::HTTP_SERVICE_UNAVAILABLE);
    }

    /**
     * @return Response
     */
    public function showNoHomepage(): Response
    {
        $output = $this->attemptToRender($this->config->get('general/homepage_template'));

        if ($output instanceof Response) {
            return $output;
        }

        return new Response('No homepage template found', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $item
     * @return Response|null
     *
     * @see Method Copied from Bolt\Controller\ErrorController::attemptToRender
     */
    private function attemptToRender(string $item): ?Response
    {
        // First, see if it's a contenttype/slug pair:
        [$contentType, $slug] = explode('/', $item . '/');

        if (!empty($contentType) && !empty($slug)) {
            // We wrap it in a try/catch, because we wouldn't want to
            // trigger a 404 within a 404 now, would we?
            try {
                return $this->detailController->record($slug, $contentType, false);
            } catch (NotFoundHttpException $e) {
                // Just continue to the next one.
            }
        }

        // Then, let's see if it's a template we can render.
        try {
            return $this->templateController->template($item);
        } catch (LoaderError $e) {
            // Just continue to the next one.
        }

        return null;
    }
}