<?php

namespace Core\Auth;

use Core\ValueObject\VO_BaseId;
use Core\ValueObject\VO_TokenPlainStr;

interface  ICanGenerateAuthTokenForUser{

  public function generateAuthToken(VO_BaseId $userId):VO_TokenPlainStr;

}