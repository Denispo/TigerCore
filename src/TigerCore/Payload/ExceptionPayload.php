<?php

namespace TigerCore\Payload;

use TigerCore\Response\ICanGetPayloadData;
use TigerCore\ValueObject\VO_PayloadKey;

class ExceptionPayload extends BasePayload {

  /**
   * @param ICanGetPayloadData|string $exceptionPayload
   */
  public function __construct(ICanGetPayloadData|string $exceptionPayload) {
    try {
      if (is_string($exceptionPayload)) {
        parent::__construct(['msg' => $exceptionPayload], false);
      } else {
        parent::__construct(['payload' => $exceptionPayload->getPayloadData()], false);
      }
    } catch (\ReflectionException $e) {
      // Toto by nemelo nikdy nastat, protoze mapFromDbData je vzdy false
    }

  }

  public function getPayloadKey(): VO_PayloadKey {
    return new VO_PayloadKey('error');
  }

}