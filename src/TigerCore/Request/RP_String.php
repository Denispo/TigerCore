<?php

namespace TigerCore\Requests;

class RP_String extends BaseRequestParam implements ICanGetParamValueAsString {

  private string $paramValue;

  protected function onSetValue(mixed $paramValue): bool {
    if (is_string($paramValue)) {
      $this->paramValue = $paramValue;
      return true;
    }

    if (is_numeric($paramValue)) {
      $this->paramValue = (string)$paramValue;
      return true;
    }
    return false;
  }

  public function getValueAsString(): string {
    return $this->paramValue;
  }
}