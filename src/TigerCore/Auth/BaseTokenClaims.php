<?php

namespace TigerCore\Auth;

class BaseTokenClaims implements ICanAddCustomTokenClaim, ICanGetTokenClaims{

  public function __construct(
    protected array $claims = []) {
  }

  public function addCustomClaim(string $name, string $value):void {
    $this->claims[$name] = $value;
  }

  public function getClaims(): array {
    return $this->claims;
  }

}