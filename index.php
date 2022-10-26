<?php
use FutureCMF\Core\Application;
require_once __DIR__ . "/vendor/autoload.php";

// Load the core:
foreach(glob(__DIR__ . "/core/**/*.php") as $file) {
    require_once $file;
}

$application = new Application();
$application->init();
echo $application->run();   