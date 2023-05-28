<?php

namespace TigerCore\ValueObject;

use TigerCore\ICanCheckSelfValidity;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsTimestamp;

class VO_Timestamp extends VO_Int implements ICanCheckSelfValidity {

  public static function createAsNow():self {
    return new self(time());
  }

  public function __construct(int|ICanGetValueAsTimestamp|ICanGetValueAsInit $unixTimestampInSeconds) {
    if ($unixTimestampInSeconds instanceof ICanGetValueAsTimestamp) {
      $unixTimestampInSeconds = $unixTimestampInSeconds->getValueAsTimestamp()->getValueAsInt();
    }
    parent::__construct($unixTimestampInSeconds);
  }

  function isValid(): bool {
    return $this->getValueAsInt() > 0;
  }

  public function addDays(int $daysCount):self{
    return new self($this->getValueAsInt() + ($daysCount * 24 * 60 * 60));
  }

  public function addSeconds(int $secondsCount):self{
    return new self($this->getValueAsInt() + $secondsCount);
  }

  public function addMinutes(int $minutesCount):self{
    return new self($this->getValueAsInt() + ($minutesCount * 60));
  }

  public function addDuration(VO_Duration $duration):self {
    return new self($this->getValueAsInt() + $duration->getValueAsInt());
  }

  public function isInFuture(): bool
  {
    return $this->getValueAsInt() > time();
  }

  public function isInPast(): bool
  {
    return !$this->isInFuture();
  }

}
