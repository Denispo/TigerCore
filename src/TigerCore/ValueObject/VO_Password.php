<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_Password extends BaseValueObject {

    #[Pure]
    public function __construct(string $password) {
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
