<?php

namespace TigerCore\Requests;


class RP_Boolean extends BaseRequestParam implements ICanGetParamValueAsBoolean {

  private bool $paramValue;

  protected function onSetValue(mixed $paramValue): bool {
    if (is_bool($paramValue)) {
      $this->paramValue = $paramValue;
      return true;
    }

    if ($paramValue === 1 || $paramValue === 0) {
      $this->paramValue = (bool)$paramValue;
      return true;
    }

    if ($paramValue === '1' || $paramValue === '0' || $paramValue === 'true' || $paramValue === 'false') {
      $this->paramValue = $paramValue === '1' || $paramValue === 'true';
      return true;
    }
    return false;
  }

  public function getValueAsBool(): bool {
    return $this->paramValue;
  }
}