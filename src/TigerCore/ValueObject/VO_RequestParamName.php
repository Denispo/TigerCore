<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanGetValueAsString;

class VO_RequestParamName extends VO_String_Trimmed {

  public function __construct(ICanGetValueAsString|string $value)
  {
    if ($value instanceof ICanGetValueAsString) {
      $value = $value->getValueAsString();
    }
    parent::__construct(strtolower($value));
  }

}
