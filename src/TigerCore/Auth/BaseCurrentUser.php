<?php

namespace TigerCore\Auth;


use App\ApiModule\Auth\IUserRole;
use App\ApiModule\Repository\ICanGetUserRole;
use App\ValueObjects\VO_UserId;

class BaseCurrentUser implements ICanGetUserRole {


  public function getUserRole(VO_UserId $userId): IUserRole {
    // TODO: Implement getUserRole() method.
  }
}