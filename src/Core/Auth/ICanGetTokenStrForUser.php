<?php

namespace Core\Auth;

use Core\ValueObject\VO_BaseId;
use Core\ValueObject\VO_TokenPlainStr;

interface ICanGetTokenStrForUser {

  public function getTokenStr(VO_BaseId $userId):VO_TokenPlainStr;

}