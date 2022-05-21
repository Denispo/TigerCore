<?php

namespace Core\Request;

use Core\ValueObject\VO_BaseId;
use Core\ValueObject\VO_Email;

interface IOnGetUserByCredentials {

  public function onGetUserIdByCredentials(string $loginName = '', VO_Email|null $loginEmail = null):VO_BaseId;

}