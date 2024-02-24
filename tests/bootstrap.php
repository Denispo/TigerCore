<?php

declare(strict_types=1);


require __DIR__ . '/../vendor/autoload.php';

const DIRECTORY_APP = __DIR__ . '/../TigerCore';
const DIRECTORY_TEMP = __DIR__ . '/_temp';

$loader = new Nette\Loaders\RobotLoader;

// directories to be indexed by RobotLoader (including subdirectories)
$loader->addDirectory(DIRECTORY_APP);

// use 'temp' directory for cache
$loader->setTempDirectory(DIRECTORY_TEMP);
$loader->register(); // Run the RobotLoader

Tester\Environment::setup();
Tester\Environment::setupFunctions(); // registruje globalni fukce. Napr. test() apod.
