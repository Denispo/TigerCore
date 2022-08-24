<?php

namespace TigerCore\Response;

use TigerCore\Payload\ExceptionPayload;
use TigerCore\Payload\IBasePayload;
use TigerCore\ValueObject\VO_PayloadKey;

class BaseResponseException extends \Exception implements IBasePayload {

  private ExceptionPayload $payload;

  public function __construct(int $httpIResponseCode, ICanGetPayloadData|string $payload = '') {
    $this->payload = new ExceptionPayload($payload);
    parent::__construct('', $httpIResponseCode);
  }

  public function getPayloadKey(): VO_PayloadKey {
    return $this->payload->getPayloadKey();
  }

  public function getPayloadData(): array {
    return $this->payload->getPayloadData();
  }
}