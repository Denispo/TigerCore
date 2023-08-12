<?php

namespace tests;

require_once __DIR__.'/../vendor/autoload.php';


use Nette\Http\UrlScript;
use Nette\Loaders\RobotLoader;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Auth\IAmCurrentUser;
use TigerCore\BaseRestRouter;
use TigerCore\Payload\BasePayload;
use TigerCore\Request\MatchedRequestData;
use TigerCore\Request\RequestParam;
use TigerCore\Request\Validator\Assert_IsArrayOfAssertableObjects;
use TigerCore\Response\ICanAddPayload;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Validator\BaseAssertableObject;
use TigerCore\Validator\DataMapper;
use TigerCore\ValueObject\VO_Duration;
use TigerCore\ValueObject\VO_Int;
use TigerCore\ValueObject\VO_PayloadKey;
use TigerCore\ValueObject\VO_RouteMask;
use TigerCore\ValueObject\VO_String;

date_default_timezone_set('Europe/Prague');

$loader = new RobotLoader();

// directories to be indexed by RobotLoader (including subdirectories)
$loader->addDirectory(__DIR__ . '/../src');
$loader->addDirectory(__DIR__ . '/../tests');

// use 'temp' directory for cache
$loader->setTempDirectory( __DIR__ . '/temp');
$loader->register(); // Run the RobotLoader


$rawData = [
  'name' => 'pepik',
  'id' => 5,
  'idlist' => [
    ['caption' => 'super'],
    ['caption' => 'veci'],
  ]
];

$dataMapper = new DataMapper($rawData);

class MyData2 extends BaseAssertableObject {

  #[RequestParam('caption')]
  public string $caption;

}

class MyData extends BaseAssertableObject {

  #[RequestParam('name')]
  public string $name;

  #[RequestParam('id')]
  public int $id;

  #[RequestParam('idlist')]
  #[Assert_IsArrayOfAssertableObjects(MyData2::class)]
  /**
   * @var MyData2[] $idList
   */
  public array $idList;

}


/**
 * @var MyData $result
 */
$result = $dataMapper->mapTo(MyData::class);

var_dump($result);
