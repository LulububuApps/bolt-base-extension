<?php

namespace Lulububu\BaseExtension\Controller;

use Bolt\Configuration\Content\ContentType;
use Bolt\Controller\Frontend\DetailControllerInterface;
use Bolt\Controller\Frontend\FrontendZoneInterface;
use Bolt\Controller\TwigAwareController;
use Bolt\Repository\ContentRepository;
use Bolt\Utils\ContentHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DetailController
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Controller
 */
class DetailController extends TwigAwareController implements FrontendZoneInterface, DetailControllerInterface
{
    /**
     * @var ContentRepository $contentRepository
     */
    private $contentRepository;

    /**
     * @var ContentHelper $contentHelper
     */
    private $contentHelper;

    /**
     * DetailController constructor.
     *
     * @param ContentRepository $contentRepository
     * @param ContentHelper $contentHelper
     */
    public function __construct(ContentRepository $contentRepository, ContentHelper $contentHelper)
    {
        $this->contentRepository = $contentRepository;
        $this->contentHelper     = $contentHelper;
    }

    /**
     * @Route(
     *     "/{contentTypeSlug}/{slugOrId}",
     *     name="record",
     *     requirements={"contentTypeSlug"="%bolt.requirement.contenttypes%"},
     *     methods={"GET|POST"}
     * )
     * @Route(
     *     "/{_locale}/{contentTypeSlug}/{slugOrId}",
     *     name="record_locale",
     *     requirements={"contentTypeSlug"="%bolt.requirement.contenttypes%", "_locale": "%app_locales%"},
     *     methods={"GET|POST"}
     * )
     *
     * @param $slugOrId
     * @param string|null $contentTypeSlug
     * @param bool $requirePublished
     * @return Response
     */
    public function record($slugOrId, ?string $contentTypeSlug = null, bool $requirePublished = true): Response
    {
        // Check if there's a record with given `$slugOrId` as slug (might be a numeric slug)
        $contentType = ContentType::factory($contentTypeSlug, $this->config->get('contenttypes'));
        $record      = $this->contentRepository->findOneBySlug($slugOrId, $contentType);

        // If we're given a number `$slugOrId`, like `page/100`, check if there's a record with that number as ID.
        if (!$record && is_numeric($slugOrId)) {
            $record = $this->contentRepository->findOneBy(['id' => (int)$slugOrId]);
        }

        $this->contentHelper->setCanonicalPath($record);

        return $this->renderSingle($record, $requirePublished);
    }

    /**
     * @param string $contentTypeSlug
     * @param string $field
     * @param string $value
     * @param bool $requirePublished
     * @return Response
     */
    public function contentByFieldValue(string $contentTypeSlug, string $field, string $value, bool $requirePublished = true): Response
    {
        $contentType = ContentType::factory($contentTypeSlug, $this->config->get('contenttypes'));
        $record      = $this->contentRepository->findOneByFieldValue($field, $value, $contentType);

        $this->contentHelper->setCanonicalPath($record);

        return $this->renderSingle($record);
    }
}
