<?php

namespace TigerCore\Response;

use TigerCore\Payload\IBasePayload;

interface ICanAddPayload {
  public function addPayload(IBasePayload $payload);

}