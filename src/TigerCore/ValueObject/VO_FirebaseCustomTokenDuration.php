<?php

namespace TigerCore\ValueObject;


use TigerCore\Exceptions\InvalidArgumentException;

class VO_FirebaseCustomTokenDuration extends VO_Duration {

  /**
   * Maximum duration is 3600 seconds (1 hour)
   * @throws InvalidArgumentException
   */
  public function __construct(int $seconds) {
    if ($seconds < 0 || $seconds > 3600 ) {
      throw new InvalidArgumentException('FirebaseCustomTokenDuration has to be in range from 0 to 3600 seconds');
    }
    parent::__construct(seconds: $seconds);
  }


}
