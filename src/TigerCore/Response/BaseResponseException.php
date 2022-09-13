<?php

namespace TigerCore\Response;

class BaseResponseException extends \Exception {

  public function __construct(int $httpIResponseCode, string $message = '', private array $customData = []) {
    parent::__construct($message, $httpIResponseCode);
  }

  public function getCustomdata():array {
    return $this->customData;
  }

}