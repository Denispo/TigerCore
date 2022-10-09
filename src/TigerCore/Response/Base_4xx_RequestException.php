<?php

namespace TigerCore\Response;

abstract class Base_4xx_RequestException extends BaseResponseException {

  private string $customErrorId;

  public function __construct(string|int $customErrorId = '', array $customData = [])
  {
    $this->customErrorId = trim((string)$customErrorId);
    parent::__construct($this->customErrorId, $customData);
  }

  public function getCustomErrorId():string
  {
    return $this->customErrorId;
  }

}