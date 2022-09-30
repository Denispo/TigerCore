<?php

use Nette\Http\IResponse;
use Nette\Loaders\RobotLoader;
use TigerCore\Response\ResponseCode;
use TigerCore\Utils\Crypt;

require_once __DIR__.'/../vendor/autoload.php';

date_default_timezone_set('Europe/Prague');

$loader = new RobotLoader();

// directories to be indexed by RobotLoader (including subdirectories)
$loader->addDirectory(__DIR__ . '/../src');
$loader->addDirectory(__DIR__ . '/../tests');

// use 'temp' directory for cache
$loader->setTempDirectory( __DIR__ . '/temp');
$loader->register(); // Run the RobotLoader

class MyException extends \TigerCore\Response\S400_BadRequestException
{

}

$e = new MyException();

echo(PHP_EOL.'Code: '.$e->getResponseCode().PHP_EOL);