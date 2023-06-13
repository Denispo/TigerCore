<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_Base64Hash extends VO_Hash {
  /**
   * @param ICanGetValueAsString|string $base64String
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $base64String)
  {
    //https://stackoverflow.com/a/11154248
    if (!(bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $base64String)) {
      throw new InvalidArgumentException('$base64String contains invalid characters:'.substr($base64String,0,100));
    }

    parent::__construct($base64String);
  }

}
