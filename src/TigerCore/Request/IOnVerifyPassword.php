<?php

namespace TigerCore\Request;

use TigerCore\ValueObject\VO_BaseId;
use TigerCore\ValueObject\VO_Password;
use TigerCore\Constants\PasswordValidity;

interface IOnVerifyPassword {

  public function onVerifyPassword(VO_Password $password, VO_BaseId $userId):PasswordValidity;

}