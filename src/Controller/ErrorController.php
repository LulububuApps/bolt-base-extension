<?php

declare(strict_types=1);

namespace Lulububu\BaseExtension\Controller;

use Bolt\Configuration\Config;
use Bolt\Controller\ErrorController as BaseErrorController;
use Bolt\Controller\Frontend\DetailController;
use Bolt\Controller\Frontend\DetailControllerInterface;
use Bolt\Controller\Frontend\TemplateController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Error\LoaderError;

/**
 * Class ErrorController
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Controller
 */
class ErrorController extends BaseErrorController
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
     * ErrorController constructor.
     *
     * @param HttpKernelInterface $httpKernel
     * @param Config $config
     * @param DetailControllerInterface $detailController
     * @param TemplateController $templateController
     * @param ErrorRendererInterface $errorRenderer
     * @param ParameterBagInterface $parameterBag
     * @param RequestStack $requestStack
     * @param UrlGeneratorInterface $urlGenerator
     * @param Security $security
     */
    public function __construct(
        HttpKernelInterface       $httpKernel,
        Config                    $config,
        DetailControllerInterface $detailController,
        TemplateController        $templateController,
        ErrorRendererInterface    $errorRenderer,
        ParameterBagInterface     $parameterBag,
        RequestStack              $requestStack,
        UrlGeneratorInterface     $urlGenerator,
        Security                  $security
    )
    {
        parent::__construct($httpKernel, $config, $detailController, $templateController, $errorRenderer, $parameterBag, $requestStack, $urlGenerator, $security);

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
     *
     * @see Method Copied from Bolt\Controller\ErrorController::showNotFound
     */
    private function showNotFound(): Response
    {
        foreach ($this->config->get('general/notfound') as $item) {
            $output = $this->attemptToRender($item);

            if ($output instanceof Response) {
                return $output;
            }
        }

        return new Response('404: Not found (and there was no proper page configured to display)');
    }

    /**
     * @return Response
     */
    public function showNoHomepage(): Response
    {
        $output = $this->attemptToRender($this->config->get('general/homepage_default_demplate'));

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
