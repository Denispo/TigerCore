<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanGetValueAsString;

class VO_Password extends BaseValueObject {

    #[Pure]
    public function __construct(string|ICanGetValueAsString $password) {
      if ($password instanceof ICanGetValueAsString) {
        $publicKey = $password->getValueAsString();
      }
      parent::__construct($password);
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
