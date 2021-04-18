<?php

namespace Lulububu\BaseExtension\Widget;

use Bolt\Widget\BaseWidget;
use Bolt\Widget\Injector\RequestZone;
use Bolt\Widget\Injector\Target;
use Bolt\Widget\TwigAwareInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LulububuInjectorWidget
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Widget
 */
class LulububuInjectorWidget extends BaseWidget implements TwigAwareInterface
{
    /**
     * @var string $name
     */
    protected $name = 'Lulububu Backend Assets';

    /**
     * @var string $target
     */
    protected $target = Target::BEFORE_HEAD_CSS;

    /**
     * @var string $zone
     */
    protected $zone = RequestZone::BACKEND;

    /**
     * @var string $templateFolder
     */
    protected $templateFolder = 'vendor/lulububu/bolt-base-extension/templates/';

    /**
     * @var string $template
     */
    protected $template = '@lulububu-backend-assets/assets.html.twig';

    /**
     * @var int $priority
     */
    protected $priority = 200;

    /**
     * @param array $params
     * @return string|null
     */
    public function run(array $params = []): ?string
    {
        $extension = $this->getExtension();
        $request   = $extension->getRequest();

        if (
            !in_array($request->get('_route'),
                [
                    'bolt_content_edit',
                    'bolt_content_new',
                    'bolt_content_duplicate',
                ],
                true
            ) ||
            (
                $request->getMethod() !== Request::METHOD_GET)
        ) {
            return null;
        }

        return parent::run();
    }
}
