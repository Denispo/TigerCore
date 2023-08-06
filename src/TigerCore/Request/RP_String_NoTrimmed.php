<?php

declare(strict_types=1);

namespace TigerCore\Request;

use TigerCore\ICanGetValueAsString;

class RP_String_NoTrimmed extends BaseRequestParam implements ICanGetValueAsString {

  protected string $paramValue = '';

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

  public function isEmpty(): bool
  {
    return $this->paramValue == '';
  }

  public function getValueAsString(string $defaultValue = ''): string {
    if ($this->isSet()) {
      return $this->paramValue;
    } else {
      return $defaultValue;
    }
  }
}