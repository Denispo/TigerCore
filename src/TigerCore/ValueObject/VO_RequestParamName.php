<?php

namespace TigerCore\ValueObject;


use TigerCore\ICanGetValueAsString;
use TigerCore\Requests\ICanGetRequestParamName;

class VO_RequestParamName extends VO_String_Trimmed implements ICanGetRequestParamName{

  public function __construct(ICanGetValueAsString|string $value)
  {
    if ($value instanceof ICanGetValueAsString) {
      $value = $value->getValueAsString();
    }
    parent::__construct(strtolower($value));
  }

  public function getParamName(): VO_RequestParamName
  {
    return $this;
  }
}
