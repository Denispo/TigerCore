<?php

namespace TigerCore\ValueObject;

class VO_CipherMethod extends BaseMappedType {

  const CIPHER_METHOD_AES_128_CTR = 1;

  private $map = [self::CIPHER_METHOD_AES_128_CTR => 'AES-128-CTR'];

  protected function onGetMap(): array
  {
    return $this->map;
  }
}
