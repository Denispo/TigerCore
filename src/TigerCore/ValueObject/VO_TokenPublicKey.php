<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanGetValueAsString;

class VO_TokenPublicKey extends BaseValueObject {

    #[Pure]
    public function __construct(string|ICanGetValueAsString $publicKey) {
      if ($publicKey instanceof ICanGetValueAsString) {
        $publicKey = $publicKey->getValueAsString();
      }
      parent::__construct($publicKey);
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
