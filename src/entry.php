<?php

require 'vendor/autoload.php';

// remove log.txt
if (file_exists('log.txt')) {
    unlink('log.txt');
}

// remove output.json
if (file_exists('output.json')) {
    unlink('output.json');
}

try {
    $scrape = new \App\Scrape();
    $scrape->run();
} catch (\Exception $e) {
    $logger = new \App\Services\LoggerService();
    $logger->error($e->getMessage());
    return 1;
}

return 0;