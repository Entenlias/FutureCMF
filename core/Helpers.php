<?php

use Pecee\Http\Request;
use Pecee\Http\Response;
use Pecee\Http\Url;
use Pecee\SimpleRouter\Router;
use Pecee\SimpleRouter\SimpleRouter;

function controller(string $controller)
{
    require_once __DIR__ . "/../site/controllers/$controller.php"; 
}

function partial(string $partial)
{
    require_once __DIR__ . "/../site/partials/$partial.php"; 
}

function parameter(string $key, mixed $default = null): mixed {
    $parameters = SimpleRouter::request()->getLoadedRoute()->getParameters();
    if(isset($parameters[$key])) return $parameters[$key];
    return $default;
}

function url(?string $name = null, $parameters = null, ?array $getParams = null): Url
{
    return SimpleRouter::getUrl($name, $parameters, $getParams);
}

/**
 * @return \Pecee\Http\Response
 */
function response(): Response
{
    return SimpleRouter::response();
}

/**
 * @return \Pecee\Http\Request
 */
function request(): Request
{
    return SimpleRouter::request();
}

/**
 * Get input class
 * @param string|null $index Parameter index name
 * @param string|mixed|null $defaultValue Default return value
 * @param array ...$methods Default methods
 * @return \Pecee\Http\Input\InputHandler|array|string|null
 */
function input($index = null, $defaultValue = null, ...$methods)
{
    if ($index !== null) {
        return request()->getInputHandler()->value($index, $defaultValue, ...$methods);
    }

    return request()->getInputHandler();
}

/**
 * @param string $url
 * @param int|null $code
 */
function redirect(string $url, ?int $code = null): void
{
    if ($code !== null) {
        response()->httpCode($code);
    }

    response()->redirect($url);
}

/**
 * Get current csrf-token
 * @return string|null
 */
function csrf_token(): ?string
{
    $baseVerifier = SimpleRouter::router()->getCsrfVerifier();
    if ($baseVerifier !== null) {
        return $baseVerifier->getTokenProvider()->getToken();
    }

    return null;
}
