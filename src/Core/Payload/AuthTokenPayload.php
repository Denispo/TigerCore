<?php

namespace Core\Payload;

use Core\ValueObject\VO_PayloadKey;

class AuthTokenPayload extends BaseTokenPayload {

  public function getPayloadKey(): VO_PayloadKey {
    return new VO_PayloadKey('tkn_auth');
  }


}