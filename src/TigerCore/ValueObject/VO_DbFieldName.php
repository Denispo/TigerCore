<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanGetValueAsString;

class VO_DbFieldName extends BaseValueObject {

    #[Pure]
    public function __construct(string|ICanGetValueAsString $dbFieldName) {
      if ($dbFieldName instanceof ICanGetValueAsString) {
        $dbFieldName = $dbFieldName->getValueAsString();
      }
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
