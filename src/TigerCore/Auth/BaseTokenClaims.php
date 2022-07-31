<?php

namespace TigerCore\Auth;

class BaseTokenClaims {

  public function __construct(
    private array $claims) {
  }

  public function getClaims(): array {
    return $this->claims;
  }

}