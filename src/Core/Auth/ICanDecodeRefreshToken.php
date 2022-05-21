<?php

namespace Core\Auth;

use Core\Exceptions\InvalidTokenException;
use Core\ValueObject\VO_TokenPlainStr;

interface ICanDecodeRefreshToken {

  /**
   * @param VO_TokenPlainStr $refreshToken
   * @return BaseDecodedTokenData
   * @throws InvalidTokenException
   */
  public function decodeRefreshToken(VO_TokenPlainStr $refreshToken):BaseDecodedTokenData;

}