<?php

namespace Lulububu\BaseExtension;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 *
 * @author Joshua Schumacher <joshua@lulububu.de>
 * @package Lulububu\BaseExtension
 */
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        dump($event);
        die();
    }
}