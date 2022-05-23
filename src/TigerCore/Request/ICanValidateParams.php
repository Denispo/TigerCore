<?php

namespace TigerCore\Request;

interface ICanValidateParams {

  public function onValidateParams($validator);

}