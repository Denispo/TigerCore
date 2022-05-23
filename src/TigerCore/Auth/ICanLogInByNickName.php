<?php

namespace TigerCore\Auth;

use App\ValueObjects\VO_NickName;
use TigerCore\ValueObject\VO_Password;

interface ICanLogInByNickName {

  public function logInByNickName(VO_NickName $nickName, VO_Password $password);

}