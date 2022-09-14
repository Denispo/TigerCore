<?php

namespace TigerCore\Auth;

use TigerCore\Constants\PasswordValidity;
use TigerCore\ValueObject\VO_PasswordPlainText;

interface ICanVerifyPasswordAgainstHash {

  public function verifyPassword(VO_PasswordPlainText $passwordPlainText, string $passwordHash):PasswordValidity;

}