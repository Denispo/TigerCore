<?php

namespace TigerCore\Request;


use TigerCore\ICanGetValueAsTimestamp;
use TigerCore\ValueObject\VO_Timestamp;

class RP_DateTime extends BaseRequestParam implements ICanGetValueAsTimestamp {

  private VO_Timestamp $paramValue;

  protected function onSetValue(mixed $paramValue):bool {

    if (is_numeric($paramValue)) {
      $paramValue = (int)$paramValue;
      if ($paramValue > 7258118401 /*rok 2200*/) {
        // timestamp je moc velky
        return false;
      }
      $this->paramValue = new VO_Timestamp($paramValue);
      return true;
    }

    if (is_string($paramValue)){
      $parsedDate = date_parse($paramValue);
      if ($parsedDate && $parsedDate['warning_count'] == 0 && $parsedDate['error_count'] == 0) {
        try {
          $this->paramValue = new VO_Timestamp((new \DateTimeImmutable($paramValue))->getTimestamp());
          return true;
        } catch (\Exception) {
          return false;
        }
      }
    }

    return false;
  }

  public function getValueAsTimestamp(): VO_Timestamp {
    if ($this->isSet()) {
      return $this->paramValue;
    } else {
      return new VO_Timestamp(0);
    }
  }
}