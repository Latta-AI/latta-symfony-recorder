<?php

namespace LattaAi\Recorder\Symfony;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class LattaSymfonyLogHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        array_push(LattaSymfonyRecorder::$logs, ["level" => "INFO", "message" => $record->formatted, "timestamp" => time()]);
    }
}