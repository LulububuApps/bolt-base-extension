<?php

namespace Lulububu\BaseExtension\Controller;

use Bolt\Configuration\Config;
use Bolt\Repository\ContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BaseController
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Controller
 */
class BaseController extends AbstractController
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var ContentRepository $contentRepository
     */
    protected $contentRepository;

    /**
     * BaseController constructor.
     *
     * @param Config $config
     * @param ContentRepository $contentRepository
     */
    public function __construct(
        Config $config,
        ContentRepository $contentRepository
    )
    {
        $this->config             = $config;
        $this->contentRepository  = $contentRepository;
    }

    /**
     * @return bool
     */
    protected function isMaintenanceMode(): bool
    {
        return $this->config->get('general/maintenance_mode');
    }

    /**
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        return !!$this->getUser();
    }
}