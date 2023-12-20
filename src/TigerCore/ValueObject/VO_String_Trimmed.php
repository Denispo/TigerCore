<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanGetValueAsString;

abstract class VO_String_Trimmed extends VO_String{

  public function __construct(string|ICanGetValueAsString $value) {
    parent::__construct($value, true);
  }



}
