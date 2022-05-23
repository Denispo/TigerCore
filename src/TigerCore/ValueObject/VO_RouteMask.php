<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_RouteMask extends BaseValueObject {

    #[Pure]
    public function __construct(string $routeMask) {
        parent::__construct(trim($routeMask));
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
