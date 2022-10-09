<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanCheckSelfEmptiness;

class VO_LastInsertedId extends BaseValueObject implements ICanCheckSelfEmptiness {

    #[Pure]
    public function __construct(string|int $id) {
      parent::__construct($id);
    }

    public function getValue():string|int {
        return $this->value;
    }

    #[pure]
    function isEmpty(): bool {
        return $this->getValue() === 0 || $this->getValue() === '';
    }
}
