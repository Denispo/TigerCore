<?php

declare(strict_types=1);

namespace TigerCore\Request;


class RP_String extends RP_String_NoTrimmed {

  protected function onSetValue(mixed $paramValue): bool {
    $result = parent::onSetValue($paramValue);
    if ($result){
      $this->paramValue = trim($this->paramValue);
    }
    return $result;
  }

}