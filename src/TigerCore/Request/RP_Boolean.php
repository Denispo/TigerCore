<?php

declare(strict_types=1);

namespace TigerCore\Requests;

use TigerCore\ICanGetValueAsBoolean;

class RP_Boolean extends BaseRequestParam implements ICanGetValueAsBoolean {

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

  public function getValueAsBool(bool $defaultValue = false): bool {
    if ($this->isSet()) {
      return $this->paramValue;
    } else {
      return $defaultValue;
    }
  }
}