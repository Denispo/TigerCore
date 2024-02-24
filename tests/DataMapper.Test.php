<?php

namespace tests;

require_once __DIR__.'/bootstrap.php';

use Tester\Assert;
use TigerCore\Request\RequestParam;
use TigerCore\Request\Validator\Assert_IsArrayOfAssertableObjects;
use TigerCore\Request\Validator\Assert_IsArrayOfValueObjects;
use TigerCore\Validator\BaseAssertableObject;
use TigerCore\Validator\DataMapper;
use TigerCore\ValueObject\VO_Base64Hash;
use TigerCore\ValueObject\VO_Duration;
use TigerCore\ValueObject\VO_Hash;

$rawData = [
  'name' => 'pepik',
  'id' => [
    'name' => 'jmeno'
  ],
  'idList' => [
    1,2,'-50',4,5
  ],
  'captionList' => [
    [
      'caption' => 'super'
    ],
    [
      'caption' => 'veci',
      'subList' => [
        [
          'caption' => 'subveci'
        ]
      ]
    ],
  ]
];



test('DataMapper default value',function()use($rawData){

  $dataMapper = new DataMapper([]);

  class RequestData extends BaseAssertableObject {

    #[RequestParam('hash','')]
    public VO_Base64Hash $hash;

  }

  $data = new RequestData();
  $dataMapper->mapTo($data);

  Assert::same($data->hash->getValueAsString(), '');

});

test('DataMapper can handle Assert_IsArrayOfAssertableObjects',function()use($rawData){
  $dataMapper = new DataMapper($rawData);

  class CaptionList extends BaseAssertableObject {

    #[RequestParam('caption')]
    public string $caption;

    #[RequestParam('subList')]
    #[Assert_IsArrayOfAssertableObjects(CaptionList::class)]
    public array|null $subList;
  }

  class NameData extends BaseAssertableObject {
    #[RequestParam('name')]
    public string $name;
  }


  class MyData extends NameData {

    #[RequestParam('noExists','default')]
    public VO_Hash $noExists;

    #[RequestParam('noExists2','default2')]
    public string $noExists2;

    #[RequestParam('captionList')]
    #[Assert_IsArrayOfAssertableObjects(CaptionList::class)]
    /**
     * @var CaptionList[] captionList
     */
    public array $captionList;

    #[RequestParam('idList')]
    #[Assert_IsArrayOfValueObjects(VO_Duration::class)]
    /**
     * @var VO_Duration[] $idList
     */
    public array $idList;

    #[RequestParam('id')]
    public NameData $id;
  }

  $data = new MyData();
  $dataMapper->mapTo($data);

  Assert::same($data->noExists->getValueAsString(), 'default');
  Assert::same($data->noExists2, 'default2');
  Assert::same($data->name, $rawData['name']);
  Assert::same($data->id->name, $rawData['id']['name']);
  Assert::same(count($data->idList),count($rawData['idList']));
  Assert::same($data->captionList[0]->caption, $rawData['captionList'][0]['caption']);
  Assert::same($data->captionList[1]->caption, $rawData['captionList'][1]['caption']);
  Assert::same($data->captionList[1]->subList[0]->caption, $rawData['captionList'][1]['subList'][0]['caption']);

});

