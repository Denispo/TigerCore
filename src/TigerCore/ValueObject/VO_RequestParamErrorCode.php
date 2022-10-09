<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanCheckSelfEmptiness;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;

class VO_RequestParamErrorCode extends BaseValueObject implements ICanCheckSelfEmptiness {

    public function __construct(string|int|ICanGetValueAsString|ICanGetValueAsInit $errorCode) {
      if ($errorCode instanceof ICanGetValueAsInit) {
        $errorCode = $errorCode->getValueAsInt();
      } elseif ($errorCode instanceof ICanGetValueAsString) {
        $errorCode = $errorCode->getValueAsString();
      }
      if (is_int($errorCode)) {
        parent::__construct((int)$errorCode);
      } else {
        parent::__construct(trim($errorCode));
      }
    }

    public function getValue():string|int {
        return $this->value;
    }

    #[pure]
    function isEmpty(): bool {
        return $this->getValue() === 0 || $this->getValue() === '';
    }
}
