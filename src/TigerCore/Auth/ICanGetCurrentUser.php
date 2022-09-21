<?php

namespace TigerCore\Auth;

interface ICanGetCurrentUser {

  public function getCurrentUser():IAmCurrentUser;

}