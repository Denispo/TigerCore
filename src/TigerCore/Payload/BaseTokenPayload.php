<?php

namespace TigerCore\Payload;

use TigerCore\ValueObject\VO_PayloadKey;
use TigerCore\ValueObject\VO_TokenPlainStr;

abstract class BaseTokenPayload extends BasePayload {

  public function __construct(VO_TokenPlainStr $tokenStr) {
    parent::__construct(['tkn' => $tokenStr], false);
  }

  public abstract function getPayloadKey():VO_PayloadKey;


}