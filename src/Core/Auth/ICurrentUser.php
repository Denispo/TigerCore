<?php

namespace Core\Auth;

interface ICurrentUser {

  public function isLoggedIn():bool;

  public function logOut();

}