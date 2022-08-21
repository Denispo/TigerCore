<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanGetValueAsString;

class VO_PayloadKey extends BaseValueObject {

    #[Pure]
    public function __construct(string|ICanGetValueAsString $payloadKey) {
      if ($payloadKey instanceof ICanGetValueAsString) {
        $payloadKey = $payloadKey->getValueAsString();
      }
      parent::__construct(strtolower(trim($payloadKey)));
    }

    public function getValue():string {
        return $this->value;
    }

    #[pure]
    function isValid(): bool {
        return !$this->isEmpty();
    }

    #[pure]
    function isEmpty(): bool {
        return $this->getValue() == '';
    }
}
