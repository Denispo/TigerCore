<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_TokenPlainStr;

interface ICanGetTokenStrForUser {

  public function getTokenStr(mixed $userId):VO_TokenPlainStr;

}