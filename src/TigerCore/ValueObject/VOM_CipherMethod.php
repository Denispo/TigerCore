<?php

namespace TigerCore\ValueObject;

// Value Object Mapped
class VOM_CipherMethod extends BaseMappedValueObject {

  const CIPHER_METHOD_AES_128_CTR = 1;

  private array $map = [self::CIPHER_METHOD_AES_128_CTR => 'AES-128-CTR'];

  protected function onGetMap(): array
  {
    return $this->map;
  }
}
