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
    // Subject. Must be a non-empty string and must be the uid of the user or device.
    // https://firebase.google.com/docs/auth/admin/verify-id-tokens
    return $this->claims['sub'] ?? '';
  }

  public function getCustomClaims(): BaseTokenClaims {
    return $this->customClaims;
  }

}