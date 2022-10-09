<?php

namespace TigerCore\Request;

use TigerCore\Requests\ICanGetRequestParamName;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RequestParam implements ICanGetRequestParamName {
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
