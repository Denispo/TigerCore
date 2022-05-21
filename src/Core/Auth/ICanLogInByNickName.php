<?php

namespace Core\Auth;

use App\ValueObjects\VO_NickName;
use Core\ValueObject\VO_Password;

interface ICanLogInByNickName {

  public function logInByNickName(VO_NickName $nickName, VO_Password $password);

}