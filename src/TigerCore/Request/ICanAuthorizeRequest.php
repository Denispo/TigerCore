<?php

namespace TigerCore\Request;

use TigerCore\Auth\ICurrentUser;

interface ICanAuthorizeRequest {

  public function onIsAuthorized(ICurrentUser $currentUser):bool;

}