<?php

namespace Core;

use Core\Auth\ICanGetCurentUser;
use Core\Constants\RequestMethod;
use Core\Request\IOnAddToPayload;
use Core\Request\ICanAuthorizeRequest;
use Core\Request\ICanMatch;
use Core\Request\RequestData;
use Core\Response\BaseResponseException;
use Core\Response\ICanAddToPayload;
use Nette\Http\IRequest;
use Nette\Routing\Route;

abstract class BaseRestRouter implements ICanMatchRoutes, ICanGetCurentUser, ICanAddToPayload {

  private function mapData(object $class, array $data):void {

    $reflection = new \ReflectionClass($class);
    $props = $reflection->getProperties();

    $data = array_change_key_case($data, CASE_LOWER);

    foreach ($props as $oneProp) {
      $attributes = $oneProp->getAttributes(RequestData::class);
      foreach ($attributes as $oneAttribute) {

        /**
         * @var RequestData $attr
         */
        $attr = $oneAttribute->newInstance();
        $paramName = $attr->getParamName();


        $value = $data[strtolower($paramName)] ?? null;

        $oneProp->setValue($class, $value);
      }
    }


  }

  /**
   * @param RequestMethod $requestMethod
   * @param ICanAddRequest $r
   */
  protected abstract function onGetRoutes(RequestMethod $requestMethod, ICanAddRequest $r);


  /**
   * @param IRequest $httpRequest
   * @return void
   */
  public function match(IRequest $httpRequest):void {
    $routeCollector = new _RouteCollector();

    $this->onGetRoutes(RequestMethod::getFromHttpRequest($httpRequest), $routeCollector);

    foreach ($routeCollector->getRoutes() as $oneRequest) {
      $params = (new Route($oneRequest->getMask()->getValue()))->match($httpRequest);
      if ($params) {

        $this->mapData($oneRequest, $params);

        if ($oneRequest instanceof ICanAuthorizeRequest) {
          $authorized = $oneRequest->onIsAuthorized($this->onGetCurrentUser());
          if (!$authorized) {
            return;
          }
        };

        if ($oneRequest instanceof ICanMatch) {
          try {
            $oneRequest->onMatch($this->onGetCurrentUser());
          } catch (BaseResponseException $e) {
            return;
          }
        };

        if ($oneRequest instanceof IOnAddToPayload) {
          try {
            $oneRequest->onAddPayload($this);
          } catch (BaseResponseException $e) {
            return;
          }
        };

        return;
      }
    }
  }
}