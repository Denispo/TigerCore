<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_Email extends VO_String_Trimmed {

  /**
   * @param string|ICanGetValueAsString $email
   * @throws InvalidArgumentException
   */
  public function __construct(string|ICanGetValueAsString $email) {
    if ($email instanceof ICanGetValueAsString) {
      $email = $email->getValueAsString();
    }
    parent::__construct(strtolower($email));
    if ($this->isEmpty()){
      throw new InvalidArgumentException('Empty email address');
    }

    if (filter_var($this->getValueAsString(), FILTER_VALIDATE_EMAIL) === false){
      throw new InvalidArgumentException('Malformed email address: "'.$this->getValueAsString().'"');
    }
  }

}
