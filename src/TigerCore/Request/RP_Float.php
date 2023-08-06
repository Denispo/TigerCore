<?php

declare(strict_types=1);

namespace TigerCore\Request;


use TigerCore\ICanGetValueAsFloat;

class RP_Float extends BaseRequestParam implements ICanGetValueAsFloat {

  private float $paramValue;

  protected function onSetValue(mixed $paramValue):bool {
    if (is_numeric($paramValue)) {
      $this->paramValue = floatval($paramValue);
      return true;
    }
    return false;
  }

  public function getValueAsFloat(float $defaultValue = 0): float
  {
    if ($this->isSet()) {
      return $this->paramValue;
    } else {
      return $defaultValue;
    }
  }
}