<?php

namespace TigerCore\Exceptions;

use Throwable;

class BaseTigerException extends \Exception
{
  /**
   * @param string $message
   * @param array $customData // Key => Value pairs of Data => Value repersenting custom data exception should show/log
   * @param Throwable|null $previousException
   * @param int $code
   */
  public function __construct(string $message = '', private readonly array $customData = [], Throwable|null $previousException = null, int $code = 0)
  {
    parent::__construct($message, $code, $previousException);
  }

  public function getCustomData(): array
  {
    return $this->customData;
  }

}
