<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_PasswordHash;
use TigerCore\ValueObject\VO_PasswordPlainText;

interface ICanGeneratePasswordHash {

  public function generatePasswordHash(VO_PasswordPlainText $passwordPlainText):VO_PasswordHash;

}