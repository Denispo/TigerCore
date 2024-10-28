<?php

namespace TigerCore\Request\Validator;

use TigerCore\ICanGetValueAsBoolean;
use TigerCore\ICanGetValueAsFloat;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;

abstract class BaseAssertion{

   protected function getValueAsInt(ICanGetValueAsInit|int $value): int
   {
      if ($value instanceof ICanGetValueAsInit){
         $value = $value->getValueAsInt();
      }
      return $value;
   }

   protected function getValueAsFloat(ICanGetValueAsFloat|float $value): float
   {
      if ($value instanceof ICanGetValueAsFloat){
         $value = $value->getValueAsFloat();
      }
      return $value;
   }

   protected function getValueAsString(ICanGetValueAsString|string $value): string
   {
      if ($value instanceof ICanGetValueAsString){
         $value = $value->getValueAsString();
      }
      return $value;
   }

   protected function getValueAsBool(ICanGetValueAsBoolean|bool $value): bool
   {
      if ($value instanceof ICanGetValueAsBoolean){
         $value = $value->getValueAsBool();
      }
      return $value;
   }

}
