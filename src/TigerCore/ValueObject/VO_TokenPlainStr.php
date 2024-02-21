<?php

namespace TigerCore\ValueObject;

use Nette\Http\IRequest;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_TokenPlainStr extends VO_String_Trimmed {

  public static function createFromBearerRequest(IRequest $request):self|null {
    $str = $request->getHeader('Authorization') ?? '';
    $str = explode(' ', $str,2);
    if ($str && $str[0] == 'Bearer') {
      $str = $str[1] ?? '';
    } else {
      $str = '';
    }
    try {
      return new self($str);
    } catch (InvalidArgumentException $e) {
      return null;
    }
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
