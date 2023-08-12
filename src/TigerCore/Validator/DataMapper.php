<?php

namespace TigerCore\Validator;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\Exceptions\TypeNotDefinedException;
use TigerCore\ICanGetValueAsBoolean;
use TigerCore\ICanGetValueAsFloat;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;
use TigerCore\ICanGetValueAsTimestamp;
use TigerCore\Request\BaseRequestParam;
use TigerCore\Request\RequestParam;
use TigerCore\Request\Validator\BaseAssertionArray;
use TigerCore\Request\Validator\BaseParamErrorCode;
use TigerCore\Request\Validator\BaseAssertion;
use TigerCore\Request\Validator\ICanAssertArrayOfAssertableObjects;
use TigerCore\Request\Validator\ICanAssertBooleanValue;
use TigerCore\Request\Validator\ICanAssertFloatValue;
use TigerCore\Request\Validator\ICanAssertIntValue;
use TigerCore\Request\Validator\ICanAssertStringValue;
use TigerCore\Request\Validator\ICanAssertTimestampValue;
use TigerCore\Request\Validator\InvalidRequestParam;
use TigerCore\ValueObject\BaseValueObject;

class DataMapper
{

  private array $invalidParams = [];

  public function __construct(private array $rawData)
  {
  }

  private function validateProperty(mixed $valueToValidate, \ReflectionProperty $property):BaseParamErrorCode|null
  {
    $attributes = $property->getAttributes(BaseAssertion::class, \ReflectionAttribute::IS_INSTANCEOF);
    foreach ($attributes as $oneAttribute) {

      /**
       * @var BaseAssertion $attrInstance
       */
      $attrInstance = $oneAttribute->newInstance();

      if (
        ($valueToValidate instanceof ICanGetValueAsInit && $attrInstance instanceof ICanAssertIntValue) ||
        ($valueToValidate instanceof ICanGetValueAsString && $attrInstance instanceof ICanAssertStringValue) ||
        ($valueToValidate instanceof ICanGetValueAsFloat && $attrInstance instanceof ICanAssertFloatValue) ||
        ($valueToValidate instanceof ICanGetValueAsTimestamp && $attrInstance instanceof ICanAssertTimestampValue) ||
        ($valueToValidate instanceof ICanGetValueAsBoolean && $attrInstance instanceof ICanAssertBooleanValue)
      ){
        $errorCode = $attrInstance->runAssertion($valueToValidate);
        if ($errorCode) {
          return $errorCode;
        }
      }

      if ($attrInstance instanceof ICanAssertArrayOfAssertableObjects && is_array($valueToValidate)) {
        foreach ($valueToValidate as $oneValueToValidate) {
          $object = new ($attrInstance->getAssertableObjectName());

          $this->runMapping();
        }
      }
    }
    return null;
  }

  /**
   * @throws InvalidArgumentException
   * @throws TypeNotDefinedException
   */
  private function mapArray(BaseAssertableObject $object, \ReflectionProperty $property, mixed $valueToAssign, string $propPathName = '')
  {
    $property->setValue($object,[]);
    if (!is_array($valueToAssign)) {
      // $valueToAssign neni typu pole, takze nelze priradit do array
      throw new InvalidArgumentException("Assertable object expects array but got no iterable value. ".$propPathName.'.'.$property->getName().'[]');
    }

    $attributes = $property->getAttributes(BaseAssertionArray::class, \ReflectionAttribute::IS_INSTANCEOF);
    foreach ($attributes as $oneAttribute) {

      /**
       * @var BaseAssertion $attrInstance
       */
      $attrInstance = $oneAttribute->newInstance();

      if ($attrInstance instanceof ICanAssertArrayOfAssertableObjects) {
        $result = [];
        foreach ($valueToAssign as $index => $oneValueToAssign) {
          $result[] = $this->runMapping($attrInstance->getAssertableObjectName(),$oneValueToAssign,$propPathName.'.'.$property->getName().'['.$index.']');
        }
        $property->setValue($object,$result);
      }
    }


  }

  /**
   * @param class-string $assertableObjectClassName
   * @param array $data Key->Value pairs of ParamName->ValueToBeMapped
   * @param string $propPathName
   * @return BaseAssertableObject Object with $data mapped on
   * @throws InvalidArgumentException|TypeNotDefinedException
   */
  private function runMapping(string $assertableObjectClassName, array $data, string $propPathName = ''):BaseAssertableObject
  {

    /**
     * @var BaseAssertableObject $object
     */
    $object = new $assertableObjectClassName;
    if (!($object instanceof BaseAssertableObject)) {
      throw new InvalidArgumentException('Parameter $assertableObjectClassName has to extends BaseAssertableObject::class');
    }

    $reflection = new \ReflectionClass($object);
    $props = $reflection->getProperties();

    foreach ($props as $oneProp) {
      $attributes = $oneProp->getAttributes(RequestParam::class);
      foreach ($attributes as $oneAttribute) {

        /**
         * @var RequestParam $attr
         */
        $attr = $oneAttribute->newInstance();
        $paramName = $attr->getParamName();


        $valueToAssign = $data[$paramName->getValueAsString()] ?? null;
        $type = $oneProp->getType();
        if (!$type) {
          throw new TypeNotDefinedException('Assertable object property has no type definition. '.$propPathName.'.'.$oneProp->getName());
        }
        if ($type->isBuiltin())   {
          // Parametr je obycejneho PHP typu (int, string, mixed, array atd.)
          if ($type->getName() === 'array') {
            // Parametr je typu array
            $this->mapArray($object, $oneProp, $valueToAssign, $propPathName);
          } else {
            // Parametr je nejakeho obycejneho PHP typu krome array
            $oneProp->setValue($object, $valueToAssign);
          }

        } else {

          if (is_a($type->getName(), BaseValueObject::class, true)) {
            // Parametr je BaseValueObject
            try {
              $valueObject = new ($type->getName())($valueToAssign);
              $oneProp->setValue($object, $valueObject);
            } catch (InvalidArgumentException) {
              // Value object se nepodarilo vytvorit (asi nelze vytvorit nevalidni)
              $this->invalidParams[] = new InvalidRequestParam($paramName, $propPathName);
            }


          } elseif (is_a($type->getName(), BaseRequestParam::class, true)) {
            // Parametr je potomkem BaseRequestParam
            $propValue = new ($type->getName())($paramName, $valueToAssign);
            $oneProp->setValue($object, $propValue);
            $result = $this->validateProperty($propValue, $oneProp);
            if ($result) {
              $this->invalidParams[] = new InvalidRequestParam($paramName, $propPathName, $result);
            }
          } elseif (is_a($type->getName(), BaseAssertableObject::class, true)) {
            // Parametr je potomkem BaseAssertableObject
            $propValue = new ($type->getName())();
            $oneProp->setValue($object, $propValue);
            // rekurzivne zavolame mapping na property $oneProp, protoze je typu BaseAssertableObject
            $this->runMapping($propValue, $valueToAssign, $propPathName . $paramName->getValueAsString() . '.');
          } else {
            // Parametr je nejaka jina trida (class, trait nebo interface), ktera neni potomkem BaseValueObject ani BaseRequestParam
          }
        }




      }
    }
    return $object;
  }

  /**
   * @param class-string $assertableObjectClassName
   * @return BaseAssertableObject
   * @throws InvalidArgumentException
   */
  public function mapTo(string $assertableObjectClassName):BaseAssertableObject
  {

    $this->invalidParams = [];

    return $this->runMapping($assertableObjectClassName, array_change_key_case($this->rawData,CASE_LOWER));

  }

}