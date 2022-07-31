<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_TokenPlainStr;

interface ICanParseTokenStr {

  public function parseToken(VO_TokenPlainStr $tokenStr):BaseTokenClaims;

}