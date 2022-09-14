<?php

namespace TigerCore\Auth;

use TigerCore\Constants\PasswordValidity;
use TigerCore\ValueObject\VO_PasswordHash;
use TigerCore\ValueObject\VO_PasswordPlainText;

interface ICanVerifyPasswordAgainstHash {

  
  public function verifyPassword(VO_PasswordPlainText $passwordPlainText, VO_PasswordHash $passwordHash):PasswordValidity;

}