<?php

namespace LattaAi\Recorder\Symfony;

use Exception;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class LattaSymfonyEventHandler
{
    public function __invoke(ExceptionEvent $event)
    {
        $lattaRecorder = new LattaSymfonyRecorder($_ENV["LATTA_API_KEY"]);

        $throwable = $event->getThrowable();
        $exception = new Exception($throwable->getMessage(), $throwable->getCode(), $throwable);

        $lattaRecorder->reportError($exception);
    }
}