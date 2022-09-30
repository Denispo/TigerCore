<?php

namespace TigerCore\Response;

class BaseResponseException extends \Exception {

  private int $httpResponseCode = 0;

  public function __construct(string $message = '', private array $customData = []) {
    parent::__construct($message);
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