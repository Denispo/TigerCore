<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsString;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AssertNoEmptyString extends BaseAssertion implements ICanAssertStringValue {

   public function __construct(private readonly bool $trimWhitespaces = true)
   {
   }

   public function runAssertion(ICanGetValueAsString|string $requestParam): BaseParamErrorCode|null
   {
      $value = parent::getValueAsString($requestParam);
      if ($this->trimWhitespaces) {
         $value = trim($value);
      }
      return $value === '' ? new ParamErrorCode_IsEmpty() : null;
   }
}
