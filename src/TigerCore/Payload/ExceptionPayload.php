<?php

namespace TigerCore\Payload;

use TigerCore\Response\ICanGetPayload;
use TigerCore\ValueObject\VO_PayloadKey;

class ExceptionPayload extends BasePayload {

  /**
   * @param ICanGetPayload|string $exceptionPayload
   */
  public function __construct(ICanGetPayload|string $exceptionPayload) {
    try {
      if (is_string($exceptionPayload)) {
        parent::__construct(['msg' => $exceptionPayload], false);
      } else {
        parent::__construct(['payload' => $exceptionPayload->getPayload()], false);
      }
    } catch (\ReflectionException $e) {
      // Toto by nemelo nikdy nastat, protoze mapFromDbData je vzdy false
    }

  }

  public function getPayloadKey(): VO_PayloadKey {
    return new VO_PayloadKey('error');
  }

}