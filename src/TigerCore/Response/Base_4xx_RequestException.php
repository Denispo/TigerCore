<?php

namespace TigerCore\Response;

use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;

abstract class Base_4xx_RequestException extends BaseResponseException {

  private string $customErrorId;

  public function __construct(string|int|ICanGetValueAsString|ICanGetValueAsInit $customErrorId = '', array $customData = [])
  {
    if ($customErrorId instanceof ICanGetValueAsString) {
      $customErrorId = $customErrorId->getValueAsString();
    } elseif ($customErrorId instanceof ICanGetValueAsInit) {
      $customErrorId = $customErrorId->getValueAsInt();
    }
    $this->customErrorId = trim((string)$customErrorId);
    parent::__construct($this->customErrorId, $customData);
  }

  public function getCustomErrorId():string
  {
    return $this->customErrorId;
  }

}