<?php

namespace TigerCore\Payload;

abstract class BasePayload implements IBasePayload{

  private array $payload;

  public function __construct(array $data) {
    $this->payload = $data;
  }

  public function getPayloadData():array {
    return $this->payload;
  }

}