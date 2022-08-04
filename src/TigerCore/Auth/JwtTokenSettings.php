<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_TokenPrivateKey;
use TigerCore\ValueObject\VO_TokenPublicKey;

class JwtTokenSettings {

  public function __construct(private ICanGetTokenPrivateKey $privateKey, private ICanGetTokenPublicKey $publicKey) {
  }

  public function getPrivateKey():VO_TokenPrivateKey {
    return $this->privateKey->getPrivateKey();
  }

  public function getPublicKey():VO_TokenPublicKey {
    return $this->publicKey->getPublicKey();
  }

}