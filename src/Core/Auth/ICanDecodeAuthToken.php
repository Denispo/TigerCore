<?php

namespace Core\Auth;

use Core\Exceptions\InvalidTokenException;
use Core\ValueObject\VO_TokenPlainStr;

interface ICanDecodeAuthToken {

  /**
   * @param VO_TokenPlainStr $authToken
   * @return BaseDecodedTokenData
   * @throws InvalidTokenException
   */
  public function decodeAuthToken(VO_TokenPlainStr $authToken):BaseDecodedTokenData;

}