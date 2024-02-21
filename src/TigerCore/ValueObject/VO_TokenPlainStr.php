<?php

namespace TigerCore\ValueObject;

use Nette\Http\IRequest;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_TokenPlainStr extends VO_String_Trimmed {

  /**
   * @throws InvalidArgumentException
   */
  public static function createFromBearerRequest(IRequest $request):self {
    $str = $request->getHeader('Authorization') ?? '';
    $str = explode(' ', $str,2);
    if ($str && $str[0] == 'Bearer') {
      $str = $str[1] ?? '';
    } else {
      $str = '';
    }
    return new self($str);
  }

  /**
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $value)
  {
    parent::__construct($value);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('Token string can not be empty');
    }
  }

}
