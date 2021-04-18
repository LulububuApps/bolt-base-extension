<?php

declare(strict_types=1);

namespace Lulububu\BaseExtension\Service;

use Bolt\Entity\Content;
use Bolt\Repository\ContentRepository;

/**
 * Class TaxonomyService
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Service
 */
class TaxonomyService
{
    /**
     * @var ContentRepository $contentRepository
     */
    protected $contentRepository;

    /**
     * TaxonomyService constructor.
     *
     * @param ContentRepository $contentRepository
     */
    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * @return Content|null
     */
    public function getMenus(): ?Content
    {
        dump($this->contentRepository);
        die();
    }
}