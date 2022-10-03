<?php

namespace TigerCore\Request\Validator;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RP_Required extends BaseRPValidator {
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
