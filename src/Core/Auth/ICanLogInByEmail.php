<?php

namespace Core\Auth;

use Core\ValueObject\VO_Email;
use Core\ValueObject\VO_Password;

interface ICanLogInByEmail {

  public function logInByNickName(VO_Email $nickName, VO_Password $password);

}