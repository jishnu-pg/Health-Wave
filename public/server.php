<?php

/**
 * Router for PHP's built-in server (Railway, Docker, local).
 * Same behavior as Laravel's vendor server script, but uses __DIR__ so cwd does not matter.
 *
 * @see vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php
 */
$publicPath = __DIR__;

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists($publicPath.$uri)) {
    return false;
}

require_once $publicPath.'/index.php';
