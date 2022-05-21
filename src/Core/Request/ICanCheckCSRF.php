<?php

namespace Core\Request;

interface ICanCheckCSRF {

  public function getCsrf():string;

}