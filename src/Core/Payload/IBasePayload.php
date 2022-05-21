<?php

namespace Core\Payload;

use Core\ValueObject\VO_PayloadKey;

interface IBasePayload{

  public function getPayloadKey():VO_PayloadKey;

  public function getPayloadData():array;

}