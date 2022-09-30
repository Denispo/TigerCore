<?php

namespace TigerCore\Response;


abstract class Base_4xx_RequestException extends BaseResponseException {

  private string $customErrorId;

  public function __construct(int $httpIResponseCode, string|int $customErrorId = '', array $customData = [])
  {
    $this->customErrorId = trim((string)$customErrorId);
    parent::__construct($httpIResponseCode, $customErrorId);
  }

  public function getCustomErrorId():string
  {
    return $this->customErrorId;
  }

}