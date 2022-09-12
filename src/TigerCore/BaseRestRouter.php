<?php

namespace TigerCore;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Request\ICanGetRequestMask;
use TigerCore\Request\ICanRunMatchedRequest;
use TigerCore\Request\MatchedRequestData;
use TigerCore\Request\RequestParam;
use TigerCore\Requests\BaseRequestParam;
use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanAddPayload;
use Nette\Http\IRequest;
use TigerCore\Response\ICanGetPayloadData;
use TigerCore\Response\MethodNotAllowedException;
use TigerCore\Response\NotFoundException;
use TigerCore\ValueObject\BaseValueObject;
use function FastRoute\simpleDispatcher;

abstract class BaseRestRouter implements ICanMatchRoutes, ICanAddRequest {

  /**
   * @var array
   */
  private array $routes = [];

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

  protected abstract function onGetPayloadContainer():ICanAddPayload;

  public function addRequest(string|array $method, ICanGetRequestMask $request):void {
    $this->routes[] = ['method' => $method,'request' => $request];
  }

  /**
   * @param IRequest $httpRequest
   * @param ICanGetCurrentUser $currentUser
   * @return ICanGetPayloadData
   * @throws BaseResponseException
   */
  public function match(IRequest $httpRequest, ICanGetCurrentUser $currentUser):ICanGetPayloadData {

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
        throw new NotFoundException('path not found');
        break;
      case Dispatcher::METHOD_NOT_ALLOWED:
        throw new MethodNotAllowedException($routeInfo[1], 'Allowed methods: '.implode(', ',$routeInfo[1]));
        break;
      case Dispatcher::FOUND:
        $oneRequest = $routeInfo[1];
        $params = $routeInfo[2];
        if ($requestMethod === 'POST' || $requestMethod === 'PUT') {
          $params = array_merge($params, $httpRequest->getPost());
        }
        break;
    }


    if (isset($oneRequest) && is_object($oneRequest)) {
      $this->mapData($oneRequest, $params);

      if ($oneRequest instanceof ICanRunMatchedRequest) {
        $requestData = new MatchedRequestData(
          currentUser: $currentUser,
          payloadContainer: $this->onGetPayloadContainer(),
          httpRequest: $httpRequest
        );
        $oneRequest->runMatchedRequest($requestData);
      };

    }
  }
}