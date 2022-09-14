<?php

namespace TigerCore\Auth;

use TigerCore\Constants\PasswordValidity;
use TigerCore\ValueObject\VO_PasswordHash;
use TigerCore\ValueObject\VO_PasswordPlainText;

class Password implements ICanVerifyPasswordAgainstHash, ICanGeneratePasswordHash{


  public function generatePasswordHash(VO_PasswordPlainText $passwordPlainText): VO_PasswordHash {
    return new VO_PasswordHash(password_hash($passwordPlainText->getValue(), PASSWORD_DEFAULT));
  }

  public function verifyPassword(VO_PasswordPlainText $passwordPlainText, VO_PasswordHash $passwordHash): PasswordValidity {
    $result = password_verify($passwordPlainText->getValue(), $passwordHash->getValue());
    if ($result) {
      return new PasswordValidity(PasswordValidity::PWD_VALID);
    } else {
      return new PasswordValidity(PasswordValidity::PWD_INVALID);
    }
  }
}