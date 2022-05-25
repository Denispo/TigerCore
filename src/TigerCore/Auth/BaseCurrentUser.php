<?php

namespace TigerCore\Auth;


use App\ApiModule\Auth\IUserRole;
use App\ApiModule\Repository\ICanGetUserRole;
use TigerCore\ValueObject\VO_BaseId;

class BaseCurrentUser implements ICanGetUserRole {


  public function getUserRole(VO_BaseId $userId): IUserRole {
    // TODO: Implement getUserRole() method.
  }
}