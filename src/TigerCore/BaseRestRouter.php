<?php

namespace TigerCore;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Payload\IAmPayloadContainer;
use TigerCore\Request\ICanRunMatchedRequest;
use TigerCore\Request\MatchedRequestData;
use TigerCore\Request\RequestParam;
use TigerCore\Request\Validator\BaseParamErrorCode;
use TigerCore\Request\Validator\BaseRequestParamValidator;
use TigerCore\Request\Validator\ICanGuardBooleanRequestParam;
use TigerCore\Request\Validator\ICanGuardFloatRequestParam;
use TigerCore\Request\Validator\ICanGuardIntRequestParam;
use TigerCore\Request\Validator\ICanGuardStrRequestParam;
use TigerCore\Request\Validator\ICanGuardTimestampRequestParam;
use TigerCore\Request\Validator\InvalidRequestParam;
use TigerCore\Requests\BaseRequestParam;
use TigerCore\Response\BaseResponseException;
use Nette\Http\IRequest;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\S405_MethodNotAllowedException;
use TigerCore\Response\S404_NotFoundException;
use TigerCore\ValueObject\BaseValueObject;
use TigerCore\ValueObject\VO_RouteMask;
use function FastRoute\simpleDispatcher;

abstract class BaseRestRouter implements ICanMatchRoutes {

  /**
   * @var array
   */
  private array $routes = [];

  /**
   * @var InvalidRequestParam[]
   */
  private array $invalidParams = [];

  private function validateParam(object $class, \ReflectionProperty $property):BaseParamErrorCode|null
  {
    $attributes = $property->getAttributes(BaseRequestParamValidator::class, \ReflectionAttribute::IS_INSTANCEOF);
    $requestParam = $property->getValue($class);
    foreach ($attributes as $oneAttribute) {

      /**
       * @var BaseRequestParamValidator $attrInstance
       */
      $attrInstance = $oneAttribute->newInstance();

      if (
        ($requestParam instanceof ICanGetValueAsInit && $attrInstance instanceof ICanGuardIntRequestParam) ||
        ($requestParam instanceof ICanGetValueAsString && $attrInstance instanceof ICanGuardStrRequestParam) ||
        ($requestParam instanceof ICanGetValueAsFloat && $attrInstance instanceof ICanGuardFloatRequestParam) ||
        ($requestParam instanceof ICanGetValueAsTimestamp && $attrInstance instanceof ICanGuardTimestampRequestParam) ||
        ($requestParam instanceof ICanGetValueAsBoolean && $attrInstance instanceof ICanGuardBooleanRequestParam)
      ){
        $result = $attrInstance->runGuard($requestParam);
        if ($result) {
          return $result;
        }
      }
    }
    return null;
  }

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


        $value = $data[$paramName->getValue()] ?? null;
        $type = $oneProp->getType();
        if ($type && !$type->isBuiltin()) {
          if (is_a($type->getName(), BaseValueObject::class, true)) {
            // Parametr je BaseValueObject
            $oneProp->setValue($class, new ($type->getName())($value));

          } elseif (is_a($type->getName(), BaseRequestParam::class, true))  {
            // Parametr je potomkem BaseRequestParam
            $tmpProp = new ($type->getName())($paramName, $value);
            $oneProp->setValue($class, $tmpProp);
            $result = $this->validateParam($class, $oneProp);
            if ($result) {
              $this->invalidParams[] = new InvalidRequestParam($paramName, $result);
            }

          } else {
            // Parametr je nejaka jina trida (class, trait nebo interface), ktera neni potomkem BaseValueObject ani BaseRequestParam
          }
        } else {
          // Parametr je obycejneho PHP typu (int, string, mixed atd.)
          $oneProp->setValue($class, $value);
        }



      }
    }


  }

  protected abstract function onGetPayloadContainer():IAmPayloadContainer;

  public function addRoute(string|array $method, VO_RouteMask $mask, ICanHandleMatchedRoute $controller):void {
    $this->routes[] = ['method' => $method, 'mask' => $mask, 'handler' => $controller];
  }

  /**
   * @param IRequest $httpRequest
   * @param ICanGetCurrentUser $currentUser
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function runMatch(IRequest $httpRequest, ICanGetCurrentUser $currentUser):ICanGetPayloadRawData {

    /**
     * @var $dispatcher Dispatcher\GroupCountBased
     */
    $dispatcher = simpleDispatcher(function(RouteCollector $r) {
      // $r->addRoute('GET', '/users', 'get_all_users_handler');
      // {id} must be a number (\d+)
      // $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
      // The /{title} suffix is optional
      //$r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
      foreach ($this->routes as $index => $oneRoute) {
        $r->addRoute($oneRoute['method'],$oneRoute['mask']->getValue(), $index);
      }
    });

    $requestMethod = strtoupper($httpRequest->getMethod());
    $routeInfo = $dispatcher->dispatch($requestMethod, $httpRequest->getUrl()->getPath());

    $params = [];
    $matchedRoute = [];

    switch ($routeInfo[0]) {
      case Dispatcher::NOT_FOUND:
        throw new S404_NotFoundException('path not found');
        break;
      case Dispatcher::METHOD_NOT_ALLOWED:
        throw new S405_MethodNotAllowedException($routeInfo[1], 'Allowed methods: '.implode(', ',$routeInfo[1]));
        break;
      case Dispatcher::FOUND:
        $matchedRoute = $this->routes[$routeInfo[1]];
        $params = $routeInfo[2];
        if ($requestMethod === 'POST' || $requestMethod === 'PUT') {
          $params = array_merge($params, $httpRequest->getPost());
        }
        break;
    }


    $container = $this->onGetPayloadContainer();

    if ($matchedRoute) {
      if (isset($matchedRoute['data']) && is_object($matchedRoute['data'])) {
        $this->mapData($matchedRoute['data'], $params);

        if ($oneRequest instanceof ICanRunMatchedRequest) {
          $requestData = new MatchedRequestData(
            currentUser: $currentUser,
            payloadContainer: $container,
            httpRequest: $httpRequest,
            invalidParams: $this->invalidParams
          );
          $oneRequest->runMatchedRequest($requestData);
          return $container;
        }

      }
    }
    return $container;
  }
}