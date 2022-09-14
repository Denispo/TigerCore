<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_PasswordPlainText;

interface ICanGeneratePasswordHash {

  public function generatePasswordHash(VO_PasswordPlainText $passwordPlainText):string;

}