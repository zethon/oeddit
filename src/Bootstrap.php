<?php declare(strict_types = 1);

namespace Example;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$environment = 'development';

/**
* Register the error handler
*/
$whoops = new \Whoops\Run;
if ($environment !== 'production') 
{
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else 
{
    $whoops->pushHandler(
        function($e)
        {
            echo 'Todo: Friendly error page and send an email to the developer';
        });
}
$whoops->register();

throw new \Exception;