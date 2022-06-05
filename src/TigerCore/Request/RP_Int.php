<?php

declare(strict_types=1);

namespace TigerCore\Requests;


class RP_Int extends BaseRequestParam implements ICanGetParamValueAsInit {

  private int $paramValue;

  public function getValueAsInt(int|null $defaultValue = null): int {
    if ($this->isSet() || $defaultValue === null) {
      return $this->paramValue;
    } else {
      return $defaultValue;
    }
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