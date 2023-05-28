<?php

namespace TigerCore\ValueObject;

use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;

class VO_RequestParamErrorCode extends VO_String_Trimmed {

    public function __construct(string|int|ICanGetValueAsString|ICanGetValueAsInit $errorCode) {
      if ($errorCode instanceof ICanGetValueAsInit) {
        $errorCode = $errorCode->getValueAsInt();
      } elseif ($errorCode instanceof ICanGetValueAsString) {
        $errorCode = $errorCode->getValueAsString();
      }
      if (is_int($errorCode)) {
        parent::__construct((string)$errorCode);
      } else {
        parent::__construct($errorCode);
      }
    }

}
