<?php

namespace Core\Response;

use Core\Payload\IBasePayload;

interface ICanAddToPayload {
  public function addToPayload(IBasePayload $payload);

}