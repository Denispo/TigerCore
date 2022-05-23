<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_BaseId;
use TigerCore\ValueObject\VO_TokenPlainStr;

interface ICanGetTokenStrForUser {

  public function getTokenStr(VO_BaseId $userId):VO_TokenPlainStr;

}