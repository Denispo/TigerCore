<?php

declare(strict_types=1);

namespace TigerCore\Requests;


use TigerCore\ICanGetValueAsInit;

class RP_Int extends BaseRequestParam implements ICanGetValueAsInit {

  private int $paramValue;

  public function getValueAsInt(int $defaultValue = 0): int {
    return $this->isSet() ? $this->paramValue : $defaultValue;
  }

  protected function onSetValue(mixed $paramValue):bool {
    if (is_int($paramValue)) {
      $this->paramValue = $paramValue;
      return true;
    }

    if (is_numeric($paramValue) && ((string)intval($paramValue) === (string)$paramValue)) {
      $this->paramValue = intval($paramValue);
      return true;
    }
    return false;
  }
}