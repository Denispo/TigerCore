<?php

use Nette\Loaders\RobotLoader;
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

$text = 'Test string';
$password = 'SuperPassword';

$encoded = Crypt::encode($text, $password);

echo($encoded."\n");

$decoded = Crypt::decode($encoded, $password);

if (assert($text === $decoded)) {
  echo("\e[32m PASS \e[0m\n");
} else{
  echo("\e[31m FAILL \e[0m\n");
}