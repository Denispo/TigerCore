<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_TokenPrivateKey extends BaseValueObject {

    #[Pure]
    public function __construct(string $privateKey) {
        parent::__construct($privateKey);
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
