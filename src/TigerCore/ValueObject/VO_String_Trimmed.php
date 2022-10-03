<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanCheckSelfEmptiness;
use TigerCore\ICanGetValueAsString;

abstract class VO_String_Trimmed extends VO_String implements ICanCheckSelfEmptiness{

  public function __construct(string|ICanGetValueAsString $value) {
    parent::__construct($value, true);
  }

  public function isEmpty(): bool
  {
    return $this->value === '';
  }

}
