<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanCheckSelfValidity;

class VO_DbFieldName extends VO_String_Trimmed implements ICanCheckSelfValidity{

  private bool|null $isValid = null;

    #[pure]
    function isValid(): bool {
      if ($this->isValid === null) {
        /**
         * \A     Start of string
         * \z     End of string
         */
        $this->isValid = preg_match('/\A([a-zA-Z0-9_]+)\z/',$this->getValue()) !== false;
      }
        return !$this->isEmpty();
    }

}
