<?php

namespace App\Data;

use App\Services\LoggerService;

class PageData
{
    public int $page;

    public string $url;

    public function __construct(int $page, string $url)
    {
        (new LoggerService())->info('PageData constructor: ' . $page . ' ' . $url);
        $this->page = $page;
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}