<?php

namespace TigerCore\Auth;

interface IAmCurrentUser {

  public function isAuthenticated():bool;

  public function getUserId():string|int;

}