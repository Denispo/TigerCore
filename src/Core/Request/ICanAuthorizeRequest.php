<?php

namespace Core\Request;

use Core\Auth\ICurrentUser;

interface ICanAuthorizeRequest {

  public function onIsAuthorized(ICurrentUser $currentUser):bool;

}