<?php

namespace TigerCore\Auth;

use TigerCore\Constants\PasswordValidity;
use TigerCore\ValueObject\VO_PasswordHash;
use TigerCore\ValueObject\VO_PasswordPlainText;

interface ICanVerifyPasswordAgainstHash {

  /**
   * @param VO_PasswordPlainText $passwordPlainText
   * @param VO_PasswordHash $passwordHash
   * @return PasswordValidity
   */
  public function verifyPassword(VO_PasswordPlainText $passwordPlainText, VO_PasswordHash $passwordHash):PasswordValidity;

}