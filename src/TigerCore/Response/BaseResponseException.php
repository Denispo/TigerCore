<?php

namespace TigerCore\Response;

use TigerCore\ICanGetValueAsBoolean;
use TigerCore\ICanGetValueAsFloat;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;
use TigerCore\ICanGetValueAsTimestamp;

class BaseResponseException extends \Exception {

  private int $httpResponseCode = 0;
  private array $customData = [];

  public function __construct(string $message = '', array $customData = []) {
    parent::__construct($message);
    foreach ($customData as $key => $value) {
      if ($value instanceof ICanGetValueAsInit) {
        $value = $value->getValueAsInt();
      } elseif ($value instanceof ICanGetValueAsString) {
        $value = $value->getValueAsString();
      } elseif ($value instanceof ICanGetValueAsBoolean) {
        $value = $value->getValueAsBool();
      } elseif ($value instanceof ICanGetValueAsFloat) {
        $value = $value->getValueAsFloat();
      } elseif ($value instanceof ICanGetValueAsTimestamp) {
        $value = $value->getValueAsTimestamp();
      }
      $this->customData[$key] = $value;
    }
  }

  private function setHttpResponseCode()
  {
    $class = $this;

    // Search from last child to BaseResponseException..
    while ($class && $this->httpResponseCode === 0) {
      // Repeat until first occurrence of ResponseCode::class attribute
      $reflection = new \ReflectionClass($class);
      $attribute = current($reflection->getAttributes(ResponseCode::class));
      $arg = $attribute ? current($attribute->getArguments()) : null;
      $this->httpResponseCode =  $arg ?? 0;
      $class = $reflection->getParentClass();
      $class = $class ? $class->getName() : '';
    }

  }

  public function getResponseCode():int{
    if ($this->httpResponseCode == 0) {
      $this->setHttpResponseCode();
    }
    return $this->httpResponseCode;
  }



  public function getCustomData():array {
    return $this->customData;
  }

}