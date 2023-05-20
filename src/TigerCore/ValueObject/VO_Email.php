<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanCheckSelfValidity;
use TigerCore\ICanGetValueAsString;

class VO_Email extends VO_String_Trimmed implements ICanCheckSelfValidity {

  private bool|null $isValid = null;

  public function __construct(string|ICanGetValueAsString $email) {
    if ($email instanceof ICanGetValueAsString) {
      $email = $email->getValueAsString();
    }
    parent::__construct(strtolower($email));
    if ($this->isEmpty()){
      throw new InvalidArgumentException('Empty email address');
    }

    if (!$this->isValid){
      throw new InvalidArgumentException('Malformed email address: "'.$this->getValue().'"');
    }
  }

  function isValid(): bool {
    if ($this->isValid === null) {
      $this->isValid = filter_var($this->getValue(), FILTER_VALIDATE_EMAIL) !== false;
    }
    return $this->isValid;
  }

}
