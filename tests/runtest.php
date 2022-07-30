<?php

namespace tests;

require_once __DIR__.'/../vendor/autoload.php';


use Nette\Http\IRequest;
use Nette\Http\UrlScript;
use Nette\Loaders\RobotLoader;
use TigerCore\Auth\ICurrentUser;
use TigerCore\BaseRestRouter;
use TigerCore\ICanAddRequest;
use TigerCore\Payload\BasePayload;
use TigerCore\Payload\IBasePayload;
use TigerCore\Request\BaseRequest;
use TigerCore\Request\RequestParam;
use TigerCore\Response\ICanAddPayload;
use TigerCore\ValueObject\VO_BaseId;
use TigerCore\ValueObject\VO_PayloadKey;
use TigerCore\ValueObject\VO_RouteMask;
use TigerCore\ValueObject\VO_Timestamp;

date_default_timezone_set('Europe/Prague');

$loader = new RobotLoader();

// directories to be indexed by RobotLoader (including subdirectories)
$loader->addDirectory(__DIR__ . '/../src');
$loader->addDirectory(__DIR__ . '/../tests');

// use 'temp' directory for cache
$loader->setTempDirectory( __DIR__ . '/temp');
$loader->register(); // Run the RobotLoader


class Payload extends BasePayload
{

    public function getPayloadKey(): VO_PayloadKey
    {
        return new VO_PayloadKey('testkey');
    }

}

class Request extends BaseRequest
{

    #[RequestParam('testparam')]
    public VO_Timestamp $testParam;

    public function getMask(): VO_RouteMask
    {
        return new VO_RouteMask('/test/<testparam>[/]');
    }

    public function runMatchedRequest(ICurrentUser $currentUser, ICanAddPayload $payload, IRequest $httpRequest): void
    {
        $payload->addPayload(new Payload(['data' => $this->testParam->getValue()]));
    }
}

class CurrentUser implements ICurrentUser
{

    public function isLoggedIn(): bool
    {
       return true;
    }

  public function getUserId(): VO_BaseId
  {
    return new VO_BaseId(0);
  }
}

class RestRouter extends BaseRestRouter
{

    protected function onGetRoutes(ICanAddRequest $r)
    {
        $r->addRequest('GET', new Request());
    }

    protected function onGetCurrentUser(): ICurrentUser
    {
        return new CurrentUser();
    }

    public function addPayload(IBasePayload $payload)
    {

        echo($payload->getPayloadKey()->getValue().PHP_EOL);
        print_r($payload->getPayloadData());
    }
}

$request = new \Nette\Http\Request(new UrlScript('http://www.test.com/test/123456'));

$currentUser = new CurrentUser();

$router = new RestRouter();
$router->match($request, $currentUser);