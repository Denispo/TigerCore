<?php

namespace TigerCore\Requests;


use TigerCore\ValueObject\VO_Timestamp;

class RP_DateTime extends BaseRequestParam implements ICanGetParamValueAsTimestamp {

  private VO_Timestamp $paramValue;

  protected function onSetValue(mixed $paramValue):bool {
    if (is_int($paramValue) && $paramValue > 0) {
      $this->paramValue = new VO_Timestamp($paramValue);
      return true;
    }

    if (is_numeric($paramValue)) {
      if ($paramValue > 999999999999) {
        // timestamp je v milisekundach
        $this->paramValue = new VO_Timestamp(round(((int)$paramValue)/1000));
      } else {
        // round musi byt, protoze $paramValue muze byt float
        $this->paramValue = new VO_Timestamp(round($paramValue));
      }
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