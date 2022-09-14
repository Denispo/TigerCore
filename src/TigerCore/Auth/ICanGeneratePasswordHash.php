<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_PasswordHash;
use TigerCore\ValueObject\VO_PasswordPlainText;

interface ICanGeneratePasswordHash {

  /**
   * @param VO_PasswordPlainText $passwordPlainText
   * @return VO_PasswordHash
   */
  public function generatePasswordHash(VO_PasswordPlainText $passwordPlainText):VO_PasswordHash;

}