<?php

namespace TigerCore\Auth;

class BaseTokenClaim implements ICanAddCustomTokenClaim, ICanGetTokenClaims{

  public function __construct(
    private array $claims = []) {
  }

  public function addCustomClaim(string $name, string $value):void {
    $this->claims[$name] = $value;
  }

  public function getClaims(): array {
    return $this->claims;
  }

}