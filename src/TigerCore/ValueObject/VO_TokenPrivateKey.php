<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_TokenPrivateKey extends VO_String_Trimmed {

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
