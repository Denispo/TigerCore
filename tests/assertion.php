<?php

namespace tests;

require_once __DIR__.'/../vendor/autoload.php';


use Nette\Http\UrlScript;
use Nette\Loaders\RobotLoader;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Auth\IAmCurrentUser;
use TigerCore\BaseRestRouter;
use TigerCore\Payload\BasePayloadData;
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
  'id' => 'name',
  'idlist' => [
    ['caption' => 'super'],
    [
      'caption' => 'veci',
      'subList' => [
        ['captiON' => 'subveci']
      ]
    ],
  ]
];

$dataMapper = new DataMapper($rawData);

class MyData2 extends BaseAssertableObject {

  #[RequestParam('caption')]
  public string $caption;

  #[RequestParam('subList')]
  #[Assert_IsArrayOfAssertableObjects(MyData2::class)]
  public array|null $subList;

}

class Neco extends BaseAssertableObject {
  #[RequestParam('name')]
  public string $name;

}


class MyData extends Neco {



  #[RequestParam('idlist')]
  #[Assert_IsArrayOfAssertableObjects(MyData2::class)]
  /**
   * @var MyData2[] $idList
   */
  public array $idList;

  #[RequestParam('id')]
  public Neco $id;


}


/**
 * @var MyData $result
 */
$result = $dataMapper->mapTo(MyData::class);

var_dump($result);
