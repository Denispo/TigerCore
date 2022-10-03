<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanCheckSelfEmptiness;

class VO_PasswordPlainText extends VO_String implements ICanCheckSelfEmptiness {

    #[pure]
    function isEmpty(): bool {
        return $this->getValue() == '';
    }
}
