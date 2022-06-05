<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_DbFieldName extends BaseValueObject {

    #[Pure]
    public function __construct(string $dbFieldName) {
      parent::__construct(trim($dbFieldName));
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
