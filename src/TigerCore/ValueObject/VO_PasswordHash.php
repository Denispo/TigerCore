<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_PasswordHash extends VO_Hash {


    public function getValue():string {
        return $this->value;
    }

    #[pure]
    function isEmpty(): bool {
        return $this->getValue() == '';
    }
}
