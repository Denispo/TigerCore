<?php

namespace TigerCore\Auth;

class BaseTokenPayload implements ICanAddTokenClaim, ICanGetTokenClaims{


  public function __construct(
    protected array $payload = []
    ) {
  }

  public function addClaim(string $name, string|int|array $value):void {
    $this->payload[$name] = $value;
  }

  public function getClaims(): array {
    return $this->payload;
  }

}