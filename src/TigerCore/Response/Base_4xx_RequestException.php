<?php

namespace TigerCore\Response;

use TigerCore\Payload\ICanGetPayloadRawData;

abstract class Base_4xx_RequestException extends BaseResponseException implements ICanGetPayloadRawData {

  public function __construct(string $publicMessage = '', private readonly array|ICanGetPayloadRawData $publicCustomData = [])
  {
    parent::__construct($publicMessage);
  }

  public function getPayloadRawData(): array|object
  {
    $result = new \stdClass();
    $result->message = $this->getMessage();
    $result->data = is_array($this->publicCustomData) ? $this->publicCustomData : $this->publicCustomData->getPayloadRawData();
    return $result;
  }

}