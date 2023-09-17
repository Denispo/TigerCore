<?php

namespace TigerCore;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Nette\Http\Response;
use TigerCore\Response\BaseResponseException;
use Nette\Http\IRequest;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\Response\S405_MethodNotAllowedException;
use TigerCore\Response\S404_NotFoundException;
use TigerCore\ValueObject\VO_RouteMask;
use function FastRoute\simpleDispatcher;

abstract class BaseRestRouter implements ICanMatchRoutes {

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

  /**
   * @param IRequest $httpRequest
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function runMatch(IRequest $httpRequest):ICanGetPayloadRawData
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

    $requestMethod = strtoupper($httpRequest->getMethod());
    $routeInfo = $dispatcher->dispatch($requestMethod, $httpRequest->getUrl()->getPath());

    switch ($routeInfo[0]) {
      case Dispatcher::NOT_FOUND:
        throw new S404_NotFoundException('path not found');
        break;
      case Dispatcher::METHOD_NOT_ALLOWED:
        if ($httpRequest->getMethod() === IRequest::Options /*OPTIONS*/) {
          // preflight
          $httpResponse = new Response();
          $httpResponse->setHeader('Access-Control-Allow-Methods', implode(', ',$routeInfo[1]));
          $httpResponse->setCode(200);
          exit;
        } else {
          throw new S405_MethodNotAllowedException($routeInfo[1], 'Allowed methods: ' . implode(', ', $routeInfo[1]));
        }
        break;
      case Dispatcher::FOUND:
      default:
        $matchedRoute = $this->routes[$routeInfo[1]];
        $params = $routeInfo[2];

        /**
         * @var $handler ICanHandleMatchedRoute
         */
        $handler = $matchedRoute['handler'];
        return $handler->handleMatchedRoute($params, $httpRequest, $matchedRoute['customData']);
        break;
    }
  }
}