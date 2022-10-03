<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanCheckSelfValidity;

class VO_DbFieldName extends VO_String_Trimmed implements ICanCheckSelfValidity{

  private bool|null $isValid = null;

    #[pure]
    function isValid(): bool {
      if ($this->isValid === null) {
        $this->isValid = preg_match('/[a-zA-Z0-9_]+/',$this->getValue()) !== false;
      }
        return !$this->isEmpty();
    }

}
