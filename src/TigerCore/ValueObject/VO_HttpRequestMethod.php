<?php

namespace TigerCore\ValueObject;

use Nette\Http\IRequest;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_HttpRequestMethod extends BaseValueObject implements ICanGetValueAsString {

  /**
   * One of Http request method. 'PUT', 'GET', 'POST', 'DELETE', 'PATCH' or 'OPTIONS'
   * @param string|ICanGetValueAsString $value
   * @throws InvalidArgumentException
   */
  public function __construct(string|ICanGetValueAsString $value) {
    if ($value instanceof ICanGetValueAsString) {
      $value = $value->getValueAsString();
    }
    $value = strtoupper($value);
    switch ($value) {
      case IRequest::Put:
      case IRequest::Get:
      case IRequest::Post:
      case IRequest::Delete:
      case IRequest::Patch:
      case IRequest::Options:{
        break;
      }
      default:{
        throw new InvalidArgumentException('Invalid request method :'.$value);
      }

    }
    parent::__construct($value);
  }

  public function isOPTIONS(): bool
  {
    return $this->getValue() === IRequest::Options;
  }

  public function getValueAsString():string {
    return $this->getValue();
  }

}
