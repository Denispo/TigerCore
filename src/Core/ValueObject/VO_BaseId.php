<?php

namespace Core\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_BaseId extends BaseValueObject {

    #[Pure]
    public function __construct(string|int $id) {
      if (is_int($id)) {
        parent::__construct((int)$id);
      } else {
        parent::__construct(strtolower(trim($id)));
      }
    }

    public function getValue():string|int {
        return $this->value;
    }

    #[pure]
    function isValid(): bool {
        return !$this->isEmpty();
    }

    #[pure]
    function isEmpty(): bool {
        return $this->getValue() === 0 || $this->getValue() != '';
    }
}
