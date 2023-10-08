<?php

namespace TigerCore\Auth;

class BaseTokenClaims implements ICanAddTokenClaim, ICanGetTokenClaims{


  public function __construct(
    protected array $claims = []
    ) {
  }

  public function addClaim(string $name, string|int|array $value):void {
    $this->claims[$name] = $value;
  }

  public function getClaims(): array {
    return $this->claims;
  }

}