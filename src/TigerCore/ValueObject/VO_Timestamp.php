<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;

class VO_Timestamp extends BaseValueObject {

    #[Pure]
    public function __construct(int $unixTimestamp) {
        parent::__construct($unixTimestamp);
    }

    public function getValue():int {
        return $this->value;
    }

    #[pure]
    function isValid(): bool {
        return $this->value > 0;
    }

    #[pure]
    function isEmpty(): bool {
        return $this->value == 0;
    }

    public function addDays(int $daysCount):self{
      return new self($this->getValue() + ($daysCount * 24 * 60 * 60));
    }

    public function addSeconds(int $secondsCount):self{
      return new self($this->getValue() + $secondsCount);
    }

    public function addMinutes(int $minutesCount):self{
      return new self($this->getValue() + ($minutesCount * 60));
    }

  public function isInFuture(): bool
  {
    return $this->getValue() > time();
  }

  public function isInPast(): bool
  {
    return !$this->isInFuture();
  }

}
