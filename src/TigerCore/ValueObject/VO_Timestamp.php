<?php

namespace TigerCore\ValueObject;

use JetBrains\PhpStorm\Pure;
use TigerCore\ICanCheckSelfValidity;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsTimestamp;

class VO_Timestamp extends VO_Int implements ICanCheckSelfValidity {

  public static function createAsNow():self {
    return new self(time());
  }

  public function __construct(int|ICanGetValueAsTimestamp|ICanGetValueAsInit $unixTimestampInSeconds) {
    if ($unixTimestampInSeconds instanceof ICanGetValueAsTimestamp) {
      $unixTimestampInSeconds = $unixTimestampInSeconds->getValueAsTimestamp()->getValue();
    }
    parent::__construct($unixTimestampInSeconds);
  }

  #[pure]
  function isValid(): bool {
    return $this->value > 0;
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

  public function addDuration(VO_Duration $duration):self {
    return new self($this->getValue() + $duration->getValue());
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
