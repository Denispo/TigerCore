<?php

namespace tests;

require_once __DIR__.'/../vendor/autoload.php';


use Nette\Http\UrlScript;
use Nette\Loaders\RobotLoader;
use TigerCore\Auth\ICanGetCurrentUser;
use TigerCore\Auth\IAmCurrentUser;
use TigerCore\BaseRestRouter;
use TigerCore\Payload\BasePayloadData;
use TigerCore\Request\MatchedRequestData;
use TigerCore\Response\ICanAddPayload;
use TigerCore\Payload\ICanGetPayloadRawData;
use TigerCore\ValueObject\VO_PayloadKey;
use TigerCore\ValueObject\VO_RouteMask;

date_default_timezone_set('Europe/Prague');

$loader = new RobotLoader();

// directories to be indexed by RobotLoader (including subdirectories)
$loader->addDirectory(__DIR__ . '/../src');
$loader->addDirectory(__DIR__ . '/../tests');

// use 'temp' directory for cache
$loader->setTempDirectory( __DIR__ . '/temp');
$loader->register(); // Run the RobotLoader


class PayloadData extends BasePayloadData
{

    public function getPayloadKey(): VO_PayloadKey
    {
        return new VO_PayloadKey('testkey');
    }

}

class Request extends BaseRoutet
{


  public function getMask(): VO_RouteMask {
    return new VO_RouteMask('');
  }

  public function runMatchedRequest(MatchedRequestData $requestData): ICanGetPayloadRawData {
    // TODO: Implement runMatchedRequest() method.
  }
}

class CurrentUser implements IAmCurrentUser, ICanGetCurrentUser
{

    public function isAuthenticated(): bool
    {
       return true;
    }

  public function getUserId(): VO_BaseId
  {
    return new VO_BaseId(0);
  }

  public function getCurrentUser(): IAmCurrentUser {
    return $this;
  }
}

class RestRouter extends BaseRestRouter
{

  protected function onGetPayloadContainer(): ICanAddPayload {
    // TODO: Implement onGetPayloadContainer() method.
  }
}

$request = new \Nette\Http\Request(new UrlScript('http://www.test.com/test/123456'));

$currentUser = new CurrentUser();

$router = new RestRouter();
$router->addRequest('GET', new Request());
$router->runMatch($request, $currentUser);