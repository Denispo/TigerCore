<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanGetValueAsString;

class VO_Email extends BaseValueObject {

  private bool $isValid;

  #[Pure]
  public function __construct(string|ICanGetValueAsString $email) {
    if ($email instanceof ICanGetValueAsString) {
      $email = $email->getValueAsString();
    }
    parent::__construct(strtolower(trim($email)));
    $this->isValid = filter_var($this->getValue(), FILTER_VALIDATE_EMAIL) !== false;
  }

  public function getValue():string {
    return $this->value;
  }

  #[pure]
  function isValid(): bool {
    return $this->isValid;
  }

  #[pure]
  function isEmpty(): bool {
    return $this->getValue() == '';
  }
}
