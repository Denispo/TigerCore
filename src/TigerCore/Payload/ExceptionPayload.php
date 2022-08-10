<?php

namespace TigerCore\Payload;

use TigerCore\Response\ICanGetPayload;
use TigerCore\ValueObject\VO_PayloadKey;

class ExceptionPayload extends BasePayload {

  public function __construct(ICanGetPayload|string $exceptionPayload) {
    if (is_string($exceptionPayload)) {
      parent::__construct(['msg' => $exceptionPayload], false);
    } else {
      parent::__construct($exceptionPayload->getPayload(), false);
    }
  }

  public function getPayloadKey(): VO_PayloadKey {
    return new VO_PayloadKey('error');
  }

}