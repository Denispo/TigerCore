<?php

namespace TigerCore\Request;

use TigerCore\Requests\ICanGetRequestParamName;
use TigerCore\ValueObject\VO_RequestParamName;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RequestParam implements ICanGetRequestParamName {
  private VO_RequestParamName $paramName;

  public function __construct(string $paramName, private $defaultValue = null) {
    $this->paramName = new VO_RequestParamName($paramName);
  }

  public function getParamName():VO_RequestParamName {
    return $this->paramName;
  }

  public function getDefaultValue() {
    return $this->defaultValue;
  }

}
