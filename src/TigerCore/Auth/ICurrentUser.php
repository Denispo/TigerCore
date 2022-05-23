<?php

namespace TigerCore\Auth;

interface ICurrentUser {

  public function isLoggedIn():bool;

  public function logOut();

}