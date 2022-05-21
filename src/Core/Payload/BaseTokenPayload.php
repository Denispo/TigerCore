<?php

namespace Core\Payload;

use Core\ValueObject\VO_PayloadKey;
use Core\ValueObject\VO_TokenPlainStr;

abstract class BaseTokenPayload extends BasePayload {

  public function __construct(VO_TokenPlainStr $tokenStr) {
    parent::__construct(['tkn' => $tokenStr]);
  }

  public abstract function getPayloadKey():VO_PayloadKey;


}