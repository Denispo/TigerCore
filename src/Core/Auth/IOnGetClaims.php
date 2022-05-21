<?php

namespace Core\Auth;

interface IOnGetClaims {

  public function onGetClaims():array;

}