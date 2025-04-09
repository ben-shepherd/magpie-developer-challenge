<?php

namespace App\Services;

/**
 * Service for handling logging operations
 * 
 * This class provides methods for logging messages to both a file and the console.
 */
class LoggerService
{
    /**
     * The path to the log file
     * 
     * @var string
     */
    protected string $logFile = 'log.txt';

    /**
     * Logs a message to the log file
     * 
     * @param string $message The message to log
     * @return void
     */
    protected function log(string $message): void
    {
        file_put_contents($this->logFile, $message . PHP_EOL, FILE_APPEND);
    }

    /**
     * Logs a message to the console
     * 
     * @param string $message The message to log
     * @return void
     */
    protected function logToConsole(string $message): void
    {
        echo $message . PHP_EOL;
    }

    /**
     * Logs an informational message to both the log file and console
     * 
     * @param string $message The informational message to log
     * @return void
     */
    public function info(string $message): void
    {
        $this->log($message);
        $this->logToConsole($message);
    }

    public function jsonPrettyPrint(array $data): void
    {
        $this->log(json_encode($data, JSON_PRETTY_PRINT));
        $this->logToConsole(json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Logs an error message with timestamp and stack trace to both the log file and console
     * 
     * @param string $message The error message to log
     * @return void
     */
    public function error(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $this->log($timestamp . ' - ERROR: ' . $message . "\n" . debug_backtrace());
        $this->logToConsole($timestamp . ' - ERROR: ' . $message . "\n" . debug_backtrace());
    }
    
}