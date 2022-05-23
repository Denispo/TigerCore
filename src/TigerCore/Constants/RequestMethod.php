<?php

namespace TigerCore\Constants;


use TigerCore\Exceptions\InvalidArgumentException;
use Nette\Http\IRequest;

class RequestMethod extends BaseConst implements IRequestMethod {

  const HTTP_NA = 0;
  const HTTP_POST = 1;
  const HTTP_GET = 2;
  const HTTP_PUT = 3;
  const HTTP_DELETE = 4;
  const HTTP_OPTION = 5;

  public static function getFromHttpRequest(IRequest $httpRequest):self {
    $method = strtoupper($httpRequest->getMethod());

    switch ($method) {
      case 'POST':{
        return new RequestMethod(RequestMethod::HTTP_POST);
      }
      case 'GET':{
        return new RequestMethod(RequestMethod::HTTP_GET);
      }
      case 'DELETE':{
        return new RequestMethod(RequestMethod::HTTP_DELETE);
      }
      case 'PUT':{
        return new RequestMethod(RequestMethod::HTTP_PUT);
      }
      default:{
        return new RequestMethod(RequestMethod::HTTP_NA);
      }
    }

  }

  /**
   * @param $httpMethod
   * @throws InvalidArgumentException
   */
  public function __construct($httpMethod) {
    parent::__construct($httpMethod);
  }

  public function getValue():int {
    return parent::getValue();
  }

  public function IsSetTo($httpMethod): bool {
    return parent::IsSetToValue($httpMethod);
  }
}
