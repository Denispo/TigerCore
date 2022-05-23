<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_Email;
use TigerCore\ValueObject\VO_Password;

interface ICanLogInByEmail {

  public function logInByNickName(VO_Email $nickName, VO_Password $password);

}