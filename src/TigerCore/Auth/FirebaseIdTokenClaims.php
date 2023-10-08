<?php

namespace TigerCore\Auth;

class FirebaseIdTokenClaims{

  private BaseTokenClaims $customClaims;

  public function __construct(
    protected array $claims = []
    )
  {
    $this->customClaims = new BaseTokenClaims($this->claims['claims']?? []);
  }

  public function getUserId(): string|int
  {
    return $this->claims['uid'] ?? '';
  }

  public function getCustomClaims(): BaseTokenClaims {
    return $this->customClaims;
  }

}