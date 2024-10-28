<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsInit;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Assert_IsInRange extends BaseAssertion implements ICanAssertIntValue {

   public function __construct(private int|float $min, private int|float $max ) {
   }

   public function runAssertion(ICanGetValueAsInit|int $requestParam): BaseParamErrorCode|null
   {
      $value = parent::getValueAsInt($requestParam);
      $errorCode = null;
      if ($value < $this->min) {
         $errorCode = new ParamErrorCode_TooLow();
      }
      if ($value > $this->max) {
         $errorCode = new ParamErrorCode_TooHigh();
      }
      return $errorCode;
   }

}
