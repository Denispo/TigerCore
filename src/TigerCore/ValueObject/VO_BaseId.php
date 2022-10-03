<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanCheckSelfEmptiness;

class VO_BaseId extends BaseValueObject implements ICanCheckSelfEmptiness {

    #[Pure]
    public function __construct(string|int $id) {
      if (is_int($id)) {
        parent::__construct((int)$id);
      } else {
        parent::__construct(trim($id));
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
