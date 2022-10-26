<?php

namespace FutureCMF\Core;

use FutureCMF\Core\Http\Router;
use FutureCMF\Core\Site\Page;
use FutureCMF\Core\Utils\FileUtils;
use Pecee\Http\Request;
use Pecee\SimpleRouter\SimpleRouter;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

require_once __DIR__ . "/Helpers.php";

class Application
{

    public array $pages = [];
    public array $schemas = [];
    public array $controllers = [];

    public function scanPages(string $dir): array
    {
        $pages = [];
        foreach (FileUtils::getDirContents($dir) as $file) {
            if (!str_ends_with($file, ".php")) continue;
            $page = str_replace(realpath($dir), "", $file);
            if (str_starts_with($page, "\\") || str_starts_with($page, "/")) {
                $page = substr($page, 1);
            }
            $pageRoute = str_replace(".php", "", $page);
            $pageRoute = str_replace("\\", "/", $pageRoute);
            $pageRoute = "/" . $pageRoute;
            if ($pageRoute == "/home") $pageRoute = "/";
            $page = new Page($file, $pageRoute);

            $pages[] = $page;
        }
        return $pages;
    }

    public function init()
    {
        $this->pages = $this->scanPages(__DIR__ . "/../site/pages");
    }

    public function run()
    {
        $requestUri = $_SERVER["REQUEST_URI"];
        foreach($this->pages as $page) {
            SimpleRouter::get($page->route, function() use($page) {
                
                require_once $page->file;   
            });
        }
        SimpleRouter::error(function (Request $request, \Exception $exception) {
                        // 404: Page was not found :/
                        $errorPath = __DIR__ . "/../../site/errors/404.php";
                        if (file_exists(__DIR__ . "/../../site/errors/404.php")) {
                            require_once($errorPath);
                        } else {
                            $phpVersion = PHP_VERSION;
                            $osVersion = php_uname();
                            echo <<<HTML
                            <center>
                            <h1>Error 404: Requested page was not found</h1>
                            <p>Please contact the administrator, if you think this was an mistake.</p>
                            <hr>
                            <small>
                            <em>PHP v$phpVersion | $osVersion</em>
                            </small>
                            </center>
                        HTML;
                        }
        });
        try {
            SimpleRouter::start();
        } catch(\Exception $exception) {}
    }
}
