<?php

namespace Core\Request;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RequestData {
  private string $paramName;

  public function __construct( string $paramName, private $defaultValue = null) {
    $this->paramName = strtolower(trim($paramName));
  }

  public function getParamName():string {
    return $this->paramName;
  }

  public function getDefaultValue() {
    return $this->defaultValue;
  }

}
