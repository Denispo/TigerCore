<?php

namespace TigerCore\Auth;

use TigerCore\Constants\PasswordValidity;
use TigerCore\ValueObject\VO_PasswordHash;
use TigerCore\ValueObject\VO_PasswordPlainText;

class Password implements ICanVerifyPasswordAgainstHash, ICanGeneratePasswordHash{


  /**
   * @param VO_PasswordPlainText $passwordPlainText
   * @return VO_PasswordHash
   */
  public function generatePasswordHash(VO_PasswordPlainText $passwordPlainText): VO_PasswordHash {
    return new VO_PasswordHash(password_hash($passwordPlainText->getValueAsString(), PASSWORD_DEFAULT));
  }

  /**
   * @param VO_PasswordPlainText $passwordPlainText
   * @param VO_PasswordHash $passwordHash
   * @return PasswordValidity
   */
  public function verifyPassword(VO_PasswordPlainText $passwordPlainText, VO_PasswordHash $passwordHash): PasswordValidity {
    $result = password_verify($passwordPlainText->getValueAsString(), $passwordHash->getValueAsString());
    if ($result) {
      return new PasswordValidity(PasswordValidity::PWD_VALID);
    } else {
      return new PasswordValidity(PasswordValidity::PWD_INVALID);
    }
  }
}