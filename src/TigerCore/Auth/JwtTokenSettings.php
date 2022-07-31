<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_Duration;
use TigerCore\ValueObject\VO_TokenPrivateKey;
use TigerCore\ValueObject\VO_TokenPublicKey;

class JwtTokenSettings {

  private VO_Duration $duration;

  public function __construct(private ICanGetTokenPrivateKey $privateKey, private ICanGetTokenPublicKey $publicKey) {
  }

  public function setDuration(VO_Duration $tokenDuration):void {
    $this->duration = $tokenDuration;
  }

  public function getDuration():VO_Duration {
    return $this->duration;
  }

  public function getPrivateKey():VO_TokenPrivateKey {
    return $this->privateKey->getPrivateKey();
  }

  public function getPublicKey():VO_TokenPublicKey {
    return $this->publicKey->getPublicKey();
  }

}