<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_Url extends VO_String_Trimmed {
  /**
   * @param string|ICanGetValueAsString $urlWithProtocol
   * @throws InvalidArgumentException
   */
  public function __construct(string|ICanGetValueAsString $urlWithProtocol)
  {
    parent::__construct($urlWithProtocol);
    if (filter_var($this->getValueAsString(), FILTER_VALIDATE_URL) === false) {
      throw new InvalidArgumentException('Mallformed Url', ['$urlWithProtocol' => $urlWithProtocol]);
    }
  }

}
