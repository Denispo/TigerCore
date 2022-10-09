<?php

namespace TigerCore;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Payload\IAmPayloadContainer;
use TigerCore\Request\ICanGetRequestMask;
use TigerCore\Request\ICanRunMatchedRequest;
use TigerCore\Request\MatchedRequestData;
use TigerCore\Request\RequestParam;
use TigerCore\Request\Validator\BaseRequestParamValidator;
use TigerCore\Request\Validator\ICanValidateBooleanRequestParam;
use TigerCore\Request\Validator\ICanValidateFloatRequestParam;
use TigerCore\Request\Validator\ICanValidateIntRequestParam;
use TigerCore\Request\Validator\ICanValidateStrRequestParam;
use TigerCore\Request\Validator\ICanValidateTimestampRequestParam;
use TigerCore\Requests\BaseRequestParam;
use TigerCore\Response\BaseResponseException;
use Nette\Http\IRequest;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\S405_MethodNotAllowedException;
use TigerCore\Response\S404_NotFoundException;
use TigerCore\ValueObject\BaseValueObject;
use function FastRoute\simpleDispatcher;

abstract class BaseRestRouter implements ICanMatchRoutes, ICanAddRequest {

  /**
   * @var array
   */
  private array $routes = [];

  private function validateParam(\ReflectionProperty $property):int|string
  {
    $attributes = $property->getAttributes(BaseRequestParamValidator::class, \ReflectionAttribute::IS_INSTANCEOF);
    foreach ($attributes as $oneAttribute) {

      /**
       * @var BaseRequestParamValidator $attrInstance
       */
      $attrInstance = $oneAttribute->newInstance();

      if (
        ($property instanceof ICanGetValueAsInit && $attrInstance instanceof ICanValidateIntRequestParam) ||
        ($property instanceof ICanGetValueAsString && $attrInstance instanceof ICanValidateStrRequestParam) ||
        ($property instanceof ICanGetValueAsFloat && $attrInstance instanceof ICanValidateFloatRequestParam) ||
        ($property instanceof ICanGetValueAsTimestamp && $attrInstance instanceof ICanValidateTimestampRequestParam) ||
        ($property instanceof ICanGetValueAsBoolean && $attrInstance instanceof ICanValidateBooleanRequestParam)
      ){
        $result = $attrInstance->checkRequestParamValidity($property);
        if ($result) {
          return $result->getErrorCodeValue();
        }
      }
    }
    return '';
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


        $value = $data[strtolower($paramName)] ?? null;
        $type = $oneProp->getType();
        if ($type && !$type->isBuiltin()) {
          if (is_a($type->getName(), BaseValueObject::class, true)) {
            // Parametr je BaseValueObject
            $oneProp->setValue($class, new ($type->getName())($value));

          } elseif (is_a($type->getName(), BaseRequestParam::class, true))  {
            // Parametr je potomkem BaseRequestParam
            $oneProp->setValue($class, new ($type->getName())($paramName, $value));
            $this->validateParam($oneProp);

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

  public function addRequest(string|array $method, ICanGetRequestMask $request):void {
    $this->routes[] = ['method' => $method,'request' => $request];
  }

  /**
   * @param IRequest $httpRequest
   * @param ICanGetCurrentUser $currentUser
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function match(IRequest $httpRequest, ICanGetCurrentUser $currentUser):ICanGetPayloadRawData {

    /**
     * @var $dispatcher Dispatcher\GroupCountBased
     */
    $dispatcher = simpleDispatcher(function(RouteCollector $r) {
      // $r->addRoute('GET', '/users', 'get_all_users_handler');
      // {id} must be a number (\d+)
      // $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
      // The /{title} suffix is optional
      //$r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
      foreach ($this->routes as $oneRoute) {
        $r->addRoute($oneRoute['method'],$oneRoute['request']->getMask()->getValue(), $oneRoute['request']);
      }
    });

    $requestMethod = strtoupper($httpRequest->getMethod());
    $routeInfo = $dispatcher->dispatch($requestMethod, $httpRequest->getUrl()->getPath());

    $params = [];

    switch ($routeInfo[0]) {
      case Dispatcher::NOT_FOUND:
        throw new S404_NotFoundException('path not found');
        break;
      case Dispatcher::METHOD_NOT_ALLOWED:
        throw new S405_MethodNotAllowedException($routeInfo[1], 'Allowed methods: '.implode(', ',$routeInfo[1]));
        break;
      case Dispatcher::FOUND:
        $oneRequest = $routeInfo[1];
        $params = $routeInfo[2];
        if ($requestMethod === 'POST' || $requestMethod === 'PUT') {
          $params = array_merge($params, $httpRequest->getPost());
        }
        break;
    }


    $container = $this->onGetPayloadContainer();

    if (isset($oneRequest) && is_object($oneRequest)) {
      $this->mapData($oneRequest, $params);

      if ($oneRequest instanceof ICanRunMatchedRequest) {
        $requestData = new MatchedRequestData(
          currentUser: $currentUser,
          payloadContainer: $container,
          httpRequest: $httpRequest
        );
        $oneRequest->runMatchedRequest($requestData);
        return $container;
      };

    }
    return $container;
  }
}