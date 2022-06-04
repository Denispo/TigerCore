<?php

namespace TigerCore\Requests;

class RP_String extends BaseRequestParam implements ICanGetParamValueAsString {

  private string $paramValue;

  protected function onSetValue(mixed $paramValue): bool {
    if (is_string($paramValue)) {
      $this->paramValue = trim($paramValue);
      return true;
    }

    if (is_numeric($paramValue)) {
      $this->paramValue = trim((string)$paramValue);
      return true;
    }
    return false;
  }

  public function isEmpty(): bool
  {
    return $this->paramValue == '';
  }

  public function getValueAsString(): string {
    return $this->paramValue;
  }
}