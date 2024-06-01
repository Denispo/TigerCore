<?php
declare(strict_types=1);

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_StringUrlEncoded extends VO_String_Trimmed
{

  /**
   * @param string|ICanGetValueAsString $stringUrlEncoded
   * @throws InvalidArgumentException
   */
  public function __construct(string|ICanGetValueAsString $stringUrlEncoded)
  {
    parent::__construct($stringUrlEncoded);
    if (urlencode(urldecode($this->getValueAsString())) === $this->getValueAsString()) {
      throw new InvalidArgumentException('$stringUrlEncoded is not url encoded', ['$stringUrlEncoded' => $stringUrlEncoded]);
    }
  }

}