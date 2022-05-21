<?php

namespace Core\Auth;

interface ICanGetCurentUser {

  public function onGetCurrentUser():ICurrentUser;

}