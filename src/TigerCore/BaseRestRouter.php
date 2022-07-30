<?php

namespace TigerCore;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use TigerCore\Auth\ICurrentUser;
use TigerCore\Request\ICanGetRequestMask;
use TigerCore\Request\ICanRunMatchedRequest;
use TigerCore\Request\RequestParam;
use TigerCore\Requests\BaseRequestParam;
use TigerCore\Response\BaseResponseException;
use TigerCore\Response\ICanAddPayload;
use Nette\Http\IRequest;
use TigerCore\ValueObject\BaseValueObject;
use function FastRoute\simpleDispatcher;

abstract class BaseRestRouter implements ICanMatchRoutes, ICanAddRequest {

  /**
   * @var array
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
   * @param ICanAddRequest $r
   */
  protected abstract function onGetRoutes(ICanAddRequest $r);

  protected abstract function onGetPayloadContainer():ICanAddPayload;

  public function addRequest(string|array $method, ICanGetRequestMask $request):void {
    $this->routes[] = ['method' => $method,'request' => $request];
  }

  /**
   * @param IRequest $httpRequest
   * @param ICurrentUser $currentUser
   * @return void
   */
  public function match(IRequest $httpRequest, ICurrentUser $currentUser):void {

    $this->onGetRoutes($this);

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
        // ... 404 Not Found
        break;
      case Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
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
        try {
          $oneRequest->runMatchedRequest($currentUser, $this->onGetPayloadContainer(), $httpRequest);
        } catch (BaseResponseException $e) {
          return;
        }
      };

    }
/*
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
   */
  }
}