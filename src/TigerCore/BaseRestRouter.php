<?php

namespace TigerCore;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Nette\Http\Response;
use TigerCore\Response\BaseResponseException;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\S405_MethodNotAllowedException;
use TigerCore\ValueObject\VO_HttpRequestMethod;
use TigerCore\ValueObject\VO_RouteMask;
use function FastRoute\simpleDispatcher;

class BaseRestRouter implements ICanMatchRoutes {

  /**
   * @var array
   */
  private array $routes = [];


  /**
   * @param 'PUT'|'GET'|'POST'|'DELETE'|'PATCH'|('PUT'|'GET'|'POST'|'DELETE'|'PATCH')[] $method
   * @param VO_RouteMask $mask
   * @param ICanHandleMatchedRoute $handler
   * @param mixed|null $customData Any data you want to pass to handleMatchedRoute()
   * @return void
   */
  public function addRoute(string|array $method, VO_RouteMask $mask, ICanHandleMatchedRoute $handler, mixed $customData = null):void {
    $this->routes[] = ['method' => $method, 'mask' => $mask, 'handler' => $handler, 'customData' => $customData];
  }

  public function getRoutesCount(): int
  {
    return count($this->routes);
  }

  /**
   * @param VO_HttpRequestMethod $requestMethod IRequest->getMethod()
   * @param string $requestUrlPath IRequest->getUrl()->getPath()
   * @return ICanGetPayloadRawData|null Return null if request do not match any path
   * @throws BaseResponseException
   */
  public function runMatch(VO_HttpRequestMethod $requestMethod, string $requestUrlPath):ICanGetPayloadRawData|null
  {

    /**
     * @var $dispatcher Dispatcher\GroupCountBased
     */
    $dispatcher = simpleDispatcher(function (RouteCollector $r) {
      // $r->addRoute('GET', '/users', 'get_all_users_handler');
      // {id} must be a number (\d+)
      // $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
      // The /{title} suffix is optional
      //$r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
      foreach ($this->routes as $index => $oneRoute) {
        $r->addRoute($oneRoute['method'], $oneRoute['mask']->getValueAsString(), $index);
      }
    });

    $routeInfo = $dispatcher->dispatch($requestMethod->getValueAsString(), $requestUrlPath);

    switch ($routeInfo[0]) {
      case Dispatcher::NOT_FOUND:
      {
        return null;
        break;
      }
      case Dispatcher::METHOD_NOT_ALLOWED:
      {
        if ($requestMethod->isOPTIONS()) {
          // preflight
          $httpResponse = new Response();
          $httpResponse->setHeader('Access-Control-Allow-Methods', implode(', ', $routeInfo[1]));
          $httpResponse->setCode(200);
          return null;
        } else {
          throw new S405_MethodNotAllowedException($routeInfo[1], 'Allowed methods: ' . implode(', ', $routeInfo[1]));
        }
        break;
      }
      case Dispatcher::FOUND:
      default:
      {

        if ($requestMethod->isOPTIONS()) {
          // preflight
          $httpResponse = new Response();
          $httpResponse->setHeader('Access-Control-Allow-Methods', $requestMethod->getValueAsString());
          $httpResponse->setCode(200);
          return null;
        }


        $matchedRoute = $this->routes[$routeInfo[1]];
        $params = $routeInfo[2];

        /**
         * @var $handler ICanHandleMatchedRoute
         */
        $handler = $matchedRoute['handler'];
        return $handler->handleMatchedRoute($params, $matchedRoute['customData']);
        break;
      }
    }
  }
}