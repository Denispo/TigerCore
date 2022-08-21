<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanGetValueAsString;

abstract class VO_String extends BaseValueObject {

    public function __construct(int|ICanGetValueAsString $value) {
      if ($value instanceof ICanGetValueAsString) {
        $value = $value->getValueAsString();
      }
      parent::__construct($value);
    }

}
