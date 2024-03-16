<?php

namespace TigerCore;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Nette\Http\IRequest;
use TigerCore\Response\BaseResponseException;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\S404_NotFoundException;
use TigerCore\Response\S405_MethodNotAllowedException;
use TigerCore\Response\S500_InternalServerErrorException;
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
   * @param ICanHandleMatchedRoute|null $handler
   * @param mixed|null $customData Any data you want to pass to handleMatchedRoute()
   * @return void
   */
  public function addRoute(string|array $method, VO_RouteMask $mask, ICanHandleMatchedRoute $handler = null, mixed $customData = null):void {
    $this->routes[] = ['method' => $method, 'mask' => $mask, 'handler' => $handler, 'customData' => $customData];
  }

  public function getRoutesCount(): int
  {
    return count($this->routes);
  }

  private function dispatch_internal(VO_HttpRequestMethod $requestMethod, string $requestUrlPath):array
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

    return $dispatcher->dispatch($requestMethod->getValueAsString(), $requestUrlPath);
  }

  /**
   * @param VO_HttpRequestMethod $requestMethod IRequest->getMethod()
   * @param string $requestUrlPath IRequest->getUrl()->getPath()
   * @return ICanGetPayloadRawData|null
   * @throws S500_InternalServerErrorException
   * @throws S404_NotFoundException
   * @throws S405_MethodNotAllowedException
   * @throws BaseResponseException
   */
  public function runMatch(VO_HttpRequestMethod $requestMethod, string $requestUrlPath):ICanGetPayloadRawData|null
  {

    if ($requestMethod->isOPTIONS()) {
      throw new S500_InternalServerErrorException('runMatch() can not handle preflight "OPTIONS" requests',['url' => $requestUrlPath]);
    }

    $routeInfo = $this->dispatch_internal($requestMethod, $requestUrlPath);

    switch ($routeInfo[0]) {
      case Dispatcher::NOT_FOUND:
      {
        return null;
      }
      case Dispatcher::METHOD_NOT_ALLOWED:
      {
        throw new S405_MethodNotAllowedException($routeInfo[1], 'Allowed methods: ' . implode(', ', $routeInfo[1]));
      }
      case Dispatcher::FOUND:
      default:
      {
        $matchedRoute = $this->routes[$routeInfo[1]];
        $params = $routeInfo[2];

        /**
         * @var $handler ICanHandleMatchedRoute|null
         */
        $handler = $matchedRoute['handler'];
        if (!$handler) {
          throw new S500_InternalServerErrorException('No handler found',['utl' => $requestUrlPath]);
        }
        return $handler->handleMatchedRoute($params, $matchedRoute['customData']);
      }
    }
  }


  /** Run this method for preflight requests. Preflight is when request header is set to "OPTIONS"
   * Method ignores all Handlers provided in addRoute(). I.e. handlers can be null
   * @param string $requestUrlPath IRequest->getUrl()->getPath()
   * @return string[] Array of allowed headers $requestUrlPath can be called with
   * @throws S404_NotFoundException
   */
  public function runMatchPreflight(string $requestUrlPath):array
  {
    $routeInfo = $this->dispatch_internal(new VO_HttpRequestMethod(IRequest::Options), $requestUrlPath);
    switch ($routeInfo[0]) {
      case Dispatcher::NOT_FOUND:
      {
        throw new S404_NotFoundException();
      }
      default:{
        return $routeInfo[1];
      }
    }
  }
}