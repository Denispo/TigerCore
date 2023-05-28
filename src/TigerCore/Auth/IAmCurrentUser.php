<?php

namespace TigerCore\Auth;

interface IAmCurrentUser {

  public function isLoggedIn():bool;

  public function getUserId():mixed;

}