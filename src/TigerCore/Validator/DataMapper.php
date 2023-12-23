<?php

namespace TigerCore\Validator;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\Exceptions\TypeNotDefinedException;
use TigerCore\ICanGetValueAsBoolean;
use TigerCore\ICanGetValueAsFloat;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;
use TigerCore\Request\RequestParam;
use TigerCore\Request\Validator\BaseAssertionArray;
use TigerCore\Request\Validator\BaseParamErrorCode;
use TigerCore\Request\Validator\BaseAssertion;
use TigerCore\Request\Validator\ICanAssertArrayOfAssertableObjects;
use TigerCore\Request\Validator\ICanAssertArrayOfValueObjects;
use TigerCore\Request\Validator\ICanAssertBooleanValue;
use TigerCore\Request\Validator\ICanAssertFloatValue;
use TigerCore\Request\Validator\ICanAssertIntValue;
use TigerCore\Request\Validator\ICanAssertStringValue;
use TigerCore\ValueObject\BaseValueObject;

class DataMapper
{

  public function __construct(private readonly array $rawData)
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
        ($valueToValidate instanceof ICanGetValueAsBoolean && $attrInstance instanceof ICanAssertBooleanValue)
      ){
        $errorCode = $attrInstance->runAssertion($valueToValidate);
        if ($errorCode) {
          return $errorCode;
        }
      }
    }
    return null;
  }

  /**
   * @throws InvalidArgumentException
   * @throws TypeNotDefinedException
   */
  private function mapArray(BaseAssertableObject $object, \ReflectionProperty $property, mixed $valueToAssign, string $propPathName = ''):void
  {
    $property->setValue($object,[]);
    if (!is_array($valueToAssign)) {
      // $valueToAssign neni typu pole, takze nelze priradit do array
      throw new InvalidArgumentException("Assertable object expects array but got no iterable value. ".$propPathName.'->'.$property->getName().'[]');
    }

    $attributes = $property->getAttributes(BaseAssertionArray::class, \ReflectionAttribute::IS_INSTANCEOF);

/*    if (count($attributes) === 0) {
      throw new InvalidArgumentException('Array type has to have one assertion of ICanAssertArray...   Path: '.$propPathName.'->'.$property->getName());
    }*/

    if (count($attributes) > 1) {
      throw new InvalidArgumentException('Property can have only one Assertable array attribute. Path: '.$propPathName.'->'.$property->getName());
    }

    /**
     * @var BaseAssertion $attrInstance
     */
    $attrInstance = $attributes[0]?->newInstance();

    if ($attrInstance instanceof ICanAssertArrayOfAssertableObjects) {
      $result = [];
      foreach ($valueToAssign as $index => $oneValueToAssign) {
        $result[] = $this->runMapping($attrInstance->getAssertableObjectClassName(), $oneValueToAssign, $propPathName . '->' . $property->getName() . '[' . $index . ']');
      }
      $property->setValue($object, $result);
    } elseif ($attrInstance instanceof ICanAssertArrayOfValueObjects){
      $valueObjectClassName = $attrInstance->getValueObjectClassName();
      $data = [];
      try {
        foreach ($valueToAssign as $index => $value) {
          $data[] = new $valueObjectClassName($value);
        }
      } catch (\Throwable $e) {
        $message = '';
        if ($e instanceof InvalidArgumentException) {
          $message = ' Message:"'.$e->getMessage().'"';
        }
        throw new InvalidArgumentException('Value object for array item can not be created.'.$message.'  Path: '.$propPathName.'->'.$property->getName().'['.$index.']');
      }
      $property->setValue($object, $data);
    } else {
      $property->setValue($object, $valueToAssign);
    }

  }

  /**
   * @param class-string|BaseAssertableObject $assertableObjectInstanceOrClassName
   * @param array $data Key->Value pairs of ParamName->ValueToBeMapped.
   * @param string $propPathName
   * @return BaseAssertableObject Object with $data mapped on
   * @throws InvalidArgumentException|TypeNotDefinedException
   */
  // $data je typu mixed i kdyz ocekavame $data pouze jako array, protoze $data nemame pod kontrolou ($data jdou od klienta) a kdyby $data byly jine nez array,
  // tak PHP vyhodi vyjimku o nekompatibilnich typech (napr. array expected but string given) a vubec se nedostaneme do tela
  // metody
  private function runMapping(string|BaseAssertableObject $assertableObjectInstanceOrClassName, mixed $data, string $propPathName = ''):BaseAssertableObject
  {

    if (!is_array($data)) {
      throw new InvalidArgumentException('Parameter $data has to be an array. Path: '.$propPathName);
    }

    /**
     * @var BaseAssertableObject $object
     */
    if (is_string($assertableObjectInstanceOrClassName)) {
      $object = new $assertableObjectInstanceOrClassName;
    } else {
      $object = $assertableObjectInstanceOrClassName;
    }

    if (!($object instanceof BaseAssertableObject)) {
      throw new InvalidArgumentException('Parameter $assertableObjectInstanceOrClassName has to extends BaseAssertableObject::class');
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
        $paramName = $attr->getCustomParamName();
        if ($paramName === '') {
          $paramName = $oneProp->getName();
        }


        $valueToAssign = $data[$paramName] ?? $attr->getDefaultValue() ?? null;
        $type = $oneProp->getType();
        if (!$type) {
          throw new TypeNotDefinedException('Assertable object property has no type definition.  Path: '.$propPathName.'->'.$oneProp->getName());
        }
        if ($valueToAssign === null) {
          if ($type->allowsNull()) {
            $oneProp->setValue($object, null);
          } else {
            if (array_key_exists($paramName, $data)) {
              // Klic v array existuje a ma hodnotu null
              throw new InvalidArgumentException('Null can not be assigned to.  Path: '.$propPathName.'->'.$oneProp->getName());
            } else {
              // Klic v array vubec neexistuje
              throw new InvalidArgumentException('Key "'.$paramName.'" do not exists in $data and Null can not be assigned to.  Path: '.$propPathName.'->'.$oneProp->getName());
            }
          }

        } else {
          // $valueToAssign neni Null
          if ($type->isBuiltin()) {
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
                $result = $this->validateProperty($valueObject, $oneProp);
              } catch (InvalidArgumentException) {
                // Value object se nepodarilo vytvorit (asi nelze vytvorit nevalidni)
                throw new InvalidArgumentException('BaseValueObject can not be created. Invalid param value.  Path: '.$propPathName.'->'.$oneProp->getName());
              }
              if ($result) {
                throw new InvalidArgumentException('Value violated guard rules. ErorCode:'.$result->getErrorCodeValue()->getValueAsString().'.  Path: '.$propPathName.'->'.$oneProp->getName());
              }
            } elseif (is_a($type->getName(), BaseAssertableObject::class, true)) {
              // Parametr je potomkem BaseAssertableObject
              // rekurzivne zavolame mapping na property $oneProp, protoze je typu BaseAssertableObject
              $newObject = $this->runMapping($type->getName(), $valueToAssign, $propPathName.'->'.$paramName);
              $oneProp->setValue($object, $newObject);
            } else {
              // Parametr je nejaka jina trida (class, trait nebo interface), ktera neni potomkem BaseValueObject a tim padem ji neumime zpracovat
              throw new InvalidArgumentException('DataMapper can not handle this property type: '.$type->getName().'.  Path: '.$propPathName.'->'.$oneProp->getName());
            }
          }
        }



      }
    }
    return $object;
  }

  /**
   * @param BaseAssertableObject $assertableObject
   * @return void
   * @throws InvalidArgumentException
   * @throws TypeNotDefinedException
   */
  public function mapTo(BaseAssertableObject $assertableObject):void
  {
    // Pokud je prvni parametr runMapping() instance objektu, data se namapuji primo na tento objekt a proto
    // nas nezajima navratova hodnota runMapping();
    // Navratova hodnota runMapping() nam totiz vrati stejny objekt, jako je $assertableObject.
    $this->runMapping($assertableObject, $this->rawData, $assertableObject::class);
  }

}