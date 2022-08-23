<?php

namespace TigerCore;

use TigerCore\Payload\IBasePayload;
use TigerCore\Response\ICanAddPayload;

class _PayloadCollector implements ICanAddPayload{

  /**
   * @var IBasePayload[]
   */
  public array $payloadArray = [];

  public function addPayload(IBasePayload $payload) {
    $this->payloadArray[] = $payload;
  }
}