<?php

namespace TigerCore\Payload;

use TigerCore\ValueObject\VO_PayloadKey;

interface IBasePayload{

  public function getPayloadKey():VO_PayloadKey;

  public function getPayloadData():array;

}