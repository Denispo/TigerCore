<?php

namespace TigerCore\Request\Validator;

use TigerCore\Validator\BaseAssertableObject;

interface ICanAssertArrayOfAssertableObjects {

  /**
   * @param BaseAssertableObject[] $requestParam
   * @return BaseParamErrorCode|null
   */
  public function runAssertion(array $requestParam):BaseParamErrorCode|null;

}
