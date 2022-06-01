<?php

namespace TigerCore\Auth;

use TigerCore\ValueObject\VO_BaseId;

interface ICurrentUser {

  public function isLoggedIn():bool;

  public function getUserId():VO_BaseId;

}