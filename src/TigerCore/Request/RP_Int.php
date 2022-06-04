<?php

namespace TigerCore\Requests;


class RP_Int extends BaseRequestParam implements ICanGetParamValueAsInit {

  private int $paramValue;

  public function getValueAsInt(): int {
    return $this->paramValue;
  }

  public function onSetValue(mixed $paramValue):bool {
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