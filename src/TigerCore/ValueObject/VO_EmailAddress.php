<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_EmailAddress extends VO_String_Trimmed {

  /**
   * @param string|ICanGetValueAsString $emailAddress
   * @throws InvalidArgumentException
   */
  public function __construct(string|ICanGetValueAsString $emailAddress) {
    if ($emailAddress instanceof ICanGetValueAsString) {
      $emailAddress = $emailAddress->getValueAsString();
    }
    parent::__construct(strtolower($emailAddress));
    if ($this->isEmpty()){
      throw new InvalidArgumentException('Empty email address');
    }

    if (filter_var($this->getValueAsString(), FILTER_VALIDATE_EMAIL) === false){
      throw new InvalidArgumentException('Malformed email address: "'.$this->getValueAsString().'"');
    }
  }

}
