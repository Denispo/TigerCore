<?php

namespace Core\Request;


abstract class BaseFormRequest extends BaseRequest implements ICanCheckCSRF {

  #[RequestData('csrf')]
  public mixed $csrf;

  public function getCsrf(): string {
    return $this->csrf;
  }

}