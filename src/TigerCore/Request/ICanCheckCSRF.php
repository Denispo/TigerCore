<?php

namespace TigerCore\Request;

interface ICanCheckCSRF {

  public function getCsrf():string;

}