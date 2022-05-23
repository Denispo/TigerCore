<?php

namespace TigerCore\Auth;

use TigerCore\Exceptions\InvalidTokenException;
use TigerCore\ValueObject\VO_TokenPlainStr;

interface ICanDecodeAuthToken {

  /**
   * @param VO_TokenPlainStr $authToken
   * @return BaseDecodedTokenData
   * @throws InvalidTokenException
   */
  public function decodeAuthToken(VO_TokenPlainStr $authToken):BaseDecodedTokenData;

}