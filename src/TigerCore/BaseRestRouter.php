<?php

namespace TigerCore;

use TigerCore\Auth\ICurrentUser;
use TigerCore\Constants\RequestMethod;
use TigerCore\Request\BaseRequest;
use TigerCore\Request\ICanGetRequestMask;
use TigerCore\Request\ICanAuthorizeRequest;
use TigerCore\Request\ICanMatch;
use TigerCore\Request\RequestParam;
use TigerCore\Requests\BaseRequestParam;
use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanAddToPayload;
use Nette\Http\IRequest;
use Nette\Routing\Route;
use TigerCore\ValueObject\BaseValueObject;

abstract class BaseRestRouter implements ICanMatchRoutes, ICanAddToPayload, ICanAddRequest {

  /**
   * @var BaseRequest[]
   */
  private array $routes;

  private function mapData(object $class, array $data):void {

    $reflection = new \ReflectionClass($class);
    $props = $reflection->getProperties();

    $data = array_change_key_case($data, CASE_LOWER);

    foreach ($props as $oneProp) {
      $attributes = $oneProp->getAttributes(RequestParam::class);
      foreach ($attributes as $oneAttribute) {

        /**
         * @var RequestParam $attr
         */
        $attr = $oneAttribute->newInstance();
        $paramName = $attr->getParamName();


        $value = $data[strtolower($paramName)] ?? null;
        $type = $oneProp->getType();
          if ($type && !$type->isBuiltin()) {
              if (is_a($type->getName(), BaseValueObject::class, true)) {
                  // Parametr je BaseValueObject
                  $oneProp->setValue($class, new ($type->getName())($value));

              } elseif (is_a($type->getName(), BaseRequestParam::class, true))  {
                $oneProp->setValue($class, new ($type->getName())($paramName, $value));

              } else {
                // Parametr je nejaka jina trida (class, trait nebo interface), ktera neni potomkem BaseValueObject ani BaseRequestParam
              }
          } else {
              // Parametr je obycejneho PHP typy (int, string, mixed atd.)
              $oneProp->setValue($class, $value);
          }



      }
    }


  }

  /**
   * @param RequestMethod $requestMethod
   * @param ICanAddRequest $r
   */
  protected abstract function onGetRoutes(RequestMethod $requestMethod, ICanAddRequest $r);

  protected abstract function onGetCurrentUser():ICurrentUser;

  public function add(ICanGetRequestMask $request) {
    $this->routes[] = $request;
  }

  /**
   * @param IRequest $httpRequest
   * @return void
   */
  public function match(IRequest $httpRequest):void {

    $this->onGetRoutes(RequestMethod::getFromHttpRequest($httpRequest), $this);

    foreach ($this->routes as $oneRequest) {
      $params = (new Route($oneRequest->getMask()->getValue()))->match($httpRequest);
      if ($params !== null) {

        $this->mapData($oneRequest, $params);

        if ($oneRequest instanceof ICanAuthorizeRequest) {
          $authorized = $oneRequest->onIsAuthorized($this->onGetCurrentUser());
          if (!$authorized) {
            return;
          }
        };

        if ($oneRequest instanceof ICanMatch) {
          try {
            $oneRequest->onMatch($this->onGetCurrentUser(), $this);
          } catch (BaseResponseException $e) {
            return;
          }
        };

        return;
      }
    }
  }
}