<?php

namespace Appolo\BoltSeo\Widget;

use Bolt\Widget\BaseWidget;
use Bolt\Widget\Injector\RequestZone;
use Bolt\Widget\Injector\Target;
use Bolt\Widget\TwigAwareInterface;

/**
 * Class LulububuInjectorWidget
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Appolo\BoltSeo\Widget
 */
class LulububuInjectorWidget extends BaseWidget implements TwigAwareInterface
{
    /**
     * @var string
     */
    protected $name = 'Lulububu Backend Assets';

    /**
     * @var string
     */
    protected $target = Target::AFTER_CSS;

    /**
     * @var string
     */
    protected $zone = RequestZone::BACKEND;

    /**
     * @var string
     */
    protected $templateFolder = 'vendor/lulububu/bolt-base-extension/templates/';

    /**
     * @var string
     */
    protected $template = '@lulububu-backend-assets/assets.html.twig';

    /**
     * @var int
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
            !\in_array($request->get('_route'),
                [
                    'bolt_content_edit',
                    'bolt_content_new',
                    'bolt_content_duplicate',
                ],
                true
            ) ||
            (
                $request->getMethod() !== 'GET')
        ) {
            return null;
        }

        return parent::run();
    }
}
