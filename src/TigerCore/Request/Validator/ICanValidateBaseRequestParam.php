<?php

namespace TigerCore\Request\Validator;


use TigerCore\Requests\BaseRequestParam;

interface ICanValidateBaseRequestParam {

  public function isRequestBaseParamValid(BaseRequestParam $requestParam):bool;

}
