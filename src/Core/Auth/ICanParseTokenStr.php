<?php

namespace Core\Auth;

use Core\ValueObject\VO_TokenPlainStr;

interface ICanParseTokenStr {

  public function parseToken(VO_TokenPlainStr $tokenStr):BaseDecodedTokenData;

}