<?php

namespace Core\Request;

use Core\ValueObject\VO_BaseId;
use Core\ValueObject\VO_Password;
use Core\Constants\PasswordValidity;

interface IOnVerifyPassword {

  public function onVerifyPassword(VO_Password $password, VO_BaseId $userId):PasswordValidity;

}