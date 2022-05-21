<?php

namespace Core\Request;

interface ICanValidateParams {

  public function onValidateParams($validator);

}