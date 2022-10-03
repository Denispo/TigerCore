<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_DbFieldName extends VO_String_Trimmed implements ICanCheckSelfValidity{

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
