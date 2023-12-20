<?php

namespace TigerCore\Request;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RequestParam {
  private string $customParamName;

  /**
   * @param string $customParamName If empty string, real property name will be used as the key name in requst JSON
   * @param $defaultValue
   */
  public function __construct(string $customParamName = '', private $defaultValue = null) {
    $this->customParamName = trim($customParamName);
  }

  public function getCustomParamName():string {
    return $this->customParamName;
  }

  public function getDefaultValue() {
    return $this->defaultValue;
  }

}
