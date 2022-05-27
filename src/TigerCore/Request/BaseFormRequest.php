<?php

namespace TigerCore\Request;


abstract class BaseFormRequest extends BaseRequest implements ICanCheckCSRF {

  #[RequestParam('csrf')]
  public mixed $csrf;

  public function getCsrf(): string {
    return $this->csrf;
  }

}