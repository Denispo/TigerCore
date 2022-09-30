<?php

namespace TigerCore\Response;

abstract class Base_4xx_RequestException extends BaseResponseException {

  private string $customErrorId;

  public function __construct(string|int $customErrorId = '', array $customData = [])
  {
    $this->customErrorId = trim((string)$customErrorId);
    parent::__construct();
  }

  public function getCustomErrorId():string
  {
    return $this->customErrorId;
  }

}