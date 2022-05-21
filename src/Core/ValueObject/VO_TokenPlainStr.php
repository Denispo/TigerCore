<?php

namespace Core\ValueObject;

use JetBrains\PhpStorm\Pure;
use Nette\Http\IRequest;

class VO_TokenPlainStr extends BaseValueObject {

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


  #[Pure]
  public function __construct(string $tokenPlainStr) {
    parent::__construct(trim($tokenPlainStr));
  }

  public function getValue():string {
    return $this->value;
  }

  #[pure]
  function isEmpty(): bool {
    return $this->value == '';
  }

  public function isValid(): bool {
    // TODO: Implement isValid() method.
    return !$this->isEmpty();
  }
}
