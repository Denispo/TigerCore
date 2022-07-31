<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_BaseId;

class BaseUserTokenData {

  public function __construct(
    private VO_BaseId $userId,
    private array $claims) {
  }

  public function getUserId(): VO_BaseId {
    return $this->userId;
  }

  public function getClaims(): array {
    return $this->claims;
  }

}