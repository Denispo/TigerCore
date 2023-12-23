<?php

namespace tests;

require_once __DIR__.'/../vendor/autoload.php';


use Nette\Loaders\RobotLoader;
use TigerCore\Request\RequestParam;
use TigerCore\Request\Validator\Assert_IsArrayOfAssertableObjects;
use TigerCore\Request\Validator\Assert_IsArrayOfValueObjects;
use TigerCore\Validator\BaseAssertableObject;
use TigerCore\Validator\DataMapper;
use TigerCore\ValueObject\VO_Duration;
use TigerCore\ValueObject\VO_Int;
use TigerCore\ValueObject\VO_Timestamp;

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
  'id' => ['name' => 'jmeno'],
  'idList2' => [1,2,'-50',4,5],
  'idlist' => [
    ['caption' => 'super'],
    [
      'caption' => 'veci',
      'subList' => [
        ['caption' => 'subveci']
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

  #[RequestParam('idList2')]
  #[Assert_IsArrayOfValueObjects(VO_Timestamp::class)]
  /**
   * @var VO_Duration[] $idList2
   */
  public array $idList2;

  #[RequestParam('id')]
  public Neco $id;


}


/**
 * @var MyData $result
 */
$data = new MyData();
$dataMapper->mapTo($data);

var_dump($data);
