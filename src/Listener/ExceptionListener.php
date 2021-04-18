<?php

declare(strict_types=1);

namespace Lulububu\BaseExtension\Listener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension\Listener
 */
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        dump($event);
        die();
    }
}