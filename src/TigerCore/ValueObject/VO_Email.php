<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_Email extends VO_String_Trimmed {

  private static int $MAX_LENGTH = 100;

  /**
   * @param int $maxLength Set to less than 1 to disable email length check
   * @return void
   */
  public static function changeEmailMaxLengtConstrain(int $maxLength = 100)
  {
    self::$MAX_LENGTH = $maxLength;
  }

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

    if ((self::$MAX_LENGTH > 0) && (strlen($this->getValueAsString()) > self::$MAX_LENGTH)) {
      throw new InvalidArgumentException('Email too long. Length: "'.strlen($this->getValueAsString()).'" emailTruncated: "'.substr($this->getValueAsString(),0,self::$MAX_LENGTH).'"');
    }
  }

}
