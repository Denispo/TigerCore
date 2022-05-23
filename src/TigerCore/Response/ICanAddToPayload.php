<?php

namespace TigerCore\Response;

use TigerCore\Payload\IBasePayload;

interface ICanAddToPayload {
  public function addToPayload(IBasePayload $payload);

}