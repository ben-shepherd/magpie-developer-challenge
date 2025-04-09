<?php

namespace App\Services;

class LoggerService
{
    protected string $logFile = 'log.txt';

    protected function log(string $message): void
    {
        file_put_contents($this->logFile, $message . PHP_EOL, FILE_APPEND);
    }

    protected function logToConsole(string $message): void
    {
        echo $message . PHP_EOL;
    }

    public function info(string $message): void
    {
        $this->log($message);
        $this->logToConsole($message);
    }

    public function error(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $this->log($timestamp . ' - ERROR: ' . $message . "\n" . debug_backtrace());
        $this->logToConsole($timestamp . ' - ERROR: ' . $message . "\n" . debug_backtrace());
    }
    
}