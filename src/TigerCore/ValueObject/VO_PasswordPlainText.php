<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_PasswordPlainText extends VO_String {


    public function getValue():string {
        return $this->value;
    }

    #[pure]
    function isEmpty(): bool {
        return $this->getValue() == '';
    }
}
