<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_PayloadKey extends BaseValueObject {

    #[Pure]
    public function __construct(string $payloadKey) {
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
