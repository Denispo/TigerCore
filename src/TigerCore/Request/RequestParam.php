<?php

namespace TigerCore\Request;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RequestParam {
  private string $paramParam;

  public function __construct( string $paramName, private $defaultValue = null) {
    $this->paramParam = strtolower(trim($paramName));
  }

  public function getParamName():string {
    return $this->paramParam;
  }

  public function getDefaultValue() {
    return $this->defaultValue;
  }

}
