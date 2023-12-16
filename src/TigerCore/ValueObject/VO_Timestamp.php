<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsTimestamp;

class VO_Timestamp extends VO_Int {

  public static function createAsNow():self {
    return new self(time());
  }

  /**
   * @param int|ICanGetValueAsTimestamp|ICanGetValueAsInit $unixTimestampInSeconds
   * @throws InvalidArgumentException
   */
  public function __construct(int|ICanGetValueAsTimestamp|ICanGetValueAsInit $unixTimestampInSeconds) {
    if ($unixTimestampInSeconds instanceof ICanGetValueAsTimestamp) {
      $unixTimestampInSeconds = $unixTimestampInSeconds->getValueAsTimestamp()->getValueAsInt();
    }
    if ($unixTimestampInSeconds < 0) {
      throw new InvalidArgumentException('Timestamp can not be negative value');
    }
    parent::__construct($unixTimestampInSeconds);
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
