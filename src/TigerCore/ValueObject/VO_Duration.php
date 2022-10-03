<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_Duration extends BaseValueObject {

  #[Pure]
  public function __construct(int $seconds = 0, int $minutes = 0, int $hours = 0, int $days = 0) {
    parent::__construct($seconds + ($minutes * 60) + ($hours * 60 * 60) + ($days * 24 * 60 * 60));

  }

  public function addMinutes(int $minutes): self {
    return new self($this->getValue()+($minutes*60));
  }

  public function addHours(int $hours): self {
    return new self($this->getValue()+($hours*60*60));
  }

  public function addDays(int $days): self {
    return new self($this->getValue()+($days*24*60*60));
  }

  public function getValue():int {
    return $this->value;
  }

  #[pure]
  function isEmpty(): bool {
    return false;
  }
}
