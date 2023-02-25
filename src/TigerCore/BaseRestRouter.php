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
use TigerCore\Response\S500_InternalServerErrorException;
use TigerCore\ValueObject\BaseValueObject;
use TigerCore\ValueObject\VO_RouteMask;
use function FastRoute\simpleDispatcher;

abstract class BaseRestRouter implements ICanMatchRoutes {

  /**
   * @var array
   */
  private array $routes = [];



  public function addRoute(string|array $method, VO_RouteMask $mask, ICanHandleMatchedRoute $controller):void {
    $this->routes[] = ['method' => $method, 'mask' => $mask, 'handler' => $controller];
  }

  /**
   * @param IRequest $httpRequest
   * @param ICanGetCurrentUser $currentUser
   * @return ICanGetPayloadRawData
   * @throws BaseResponseException
   */
  public function runMatch(IRequest $httpRequest, ICanGetCurrentUser $currentUser):ICanGetPayloadRawData
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
        $r->addRoute($oneRoute['method'], $oneRoute['mask']->getValue(), $index);
      }
    });

    $requestMethod = strtoupper($httpRequest->getMethod());
    $routeInfo = $dispatcher->dispatch($requestMethod, $httpRequest->getUrl()->getPath());

    switch ($routeInfo[0]) {
      case Dispatcher::NOT_FOUND:
        throw new S404_NotFoundException('path not found');
        break;
      case Dispatcher::METHOD_NOT_ALLOWED:
        throw new S405_MethodNotAllowedException($routeInfo[1], 'Allowed methods: ' . implode(', ', $routeInfo[1]));
        break;
      case Dispatcher::FOUND:
      default:
        $matchedRoute = $this->routes[$routeInfo[1]];
        $params = $routeInfo[2];
        if ($requestMethod === 'POST' || $requestMethod === 'PUT') {
          $params = array_merge($params, $httpRequest->getPost());
        }

        /**
         * @var $handler ICanHandleMatchedRoute
         */
        $handler = $matchedRoute['handler'];
        return $handler->handleMatchedRoute($params);
        break;
    }
  }
}