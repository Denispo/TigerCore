<?php

namespace Core\Auth;

use Core\ValueObject\VO_BaseId;
use Core\ValueObject\VO_TokenPlainStr;

interface  ICanGenerateRefreshTokenForUser{

  public function generateRefreshToken(VO_BaseId $userId):VO_TokenPlainStr;

}