<?php

namespace FutureCMF\Core\Site;

require_once __DIR__ . "/../Application.php";

class Page
{

    public function __construct(public string $file, public string $route)
    {
    }

    public function isRoute(string $uri): bool {
        
        return false;
    }
}
