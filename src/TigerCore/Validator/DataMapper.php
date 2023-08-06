<?php

namespace TigerCore\Validator;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsBoolean;
use TigerCore\ICanGetValueAsFloat;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;
use TigerCore\ICanGetValueAsTimestamp;
use TigerCore\Request\BaseRequestParam;
use TigerCore\Request\RequestParam;
use TigerCore\Request\Validator\BaseParamErrorCode;
use TigerCore\Request\Validator\BaseRequestParamValidator;
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

  private function validateProperty(BaseAssertableObject $class, \ReflectionProperty $property):BaseParamErrorCode|null
  {
    $attributes = $property->getAttributes(BaseRequestParamValidator::class, \ReflectionAttribute::IS_INSTANCEOF);
    $requestParam = $property->getValue($class);
    foreach ($attributes as $oneAttribute) {

      /**
       * @var BaseRequestParamValidator $attrInstance
       */
      $attrInstance = $oneAttribute->newInstance();

      if (
        ($requestParam instanceof ICanGetValueAsInit && $attrInstance instanceof ICanAssertIntValue) ||
        ($requestParam instanceof ICanGetValueAsString && $attrInstance instanceof ICanAssertStringValue) ||
        ($requestParam instanceof ICanGetValueAsFloat && $attrInstance instanceof ICanAssertFloatValue) ||
        ($requestParam instanceof ICanGetValueAsTimestamp && $attrInstance instanceof ICanAssertTimestampValue) ||
        ($requestParam instanceof ICanGetValueAsBoolean && $attrInstance instanceof ICanAssertBooleanValue)
      ){
        $errorCode = $attrInstance->runAssertion($requestParam);
        if ($errorCode) {
          return $errorCode;
        }
      }
    }
    return null;
  }

  private function runMapping(BaseAssertableObject $object, array $data)
  {

    $reflection = new \ReflectionClass($object);
    $props = $reflection->getProperties();

    $data = array_change_key_case($data, CASE_LOWER);

    foreach ($props as $oneProp) {
      $attributes = $oneProp->getAttributes(RequestParam::class);
      foreach ($attributes as $oneAttribute) {

        /**
         * @var RequestParam $attr
         */
        $attr = $oneAttribute->newInstance();
        $paramName = $attr->getParamName();


        $value = $data[$paramName->getValueAsString()] ?? null;
        $type = $oneProp->getType();
        if ($type && !$type->isBuiltin()) {
          if (is_a($type->getName(), BaseValueObject::class, true)) {
            // Parametr je BaseValueObject
            try {
              $valueObject =  new ($type->getName())($value);
              $oneProp->setValue($object,$valueObject);
            } catch (InvalidArgumentException){
              // Value object se nepodarilo vytvorit (asi nelze vytvorit nevalidni)
              $this->invalidParams[] = new InvalidRequestParam($paramName);
            }


          } elseif (is_a($type->getName(), BaseRequestParam::class, true))  {
            // Parametr je potomkem BaseRequestParam
            $tmpProp = new ($type->getName())($paramName, $value);
            $oneProp->setValue($object, $tmpProp);
            $result = $this->validateProperty($object, $oneProp);
            if ($result) {
              $this->invalidParams[] = new InvalidRequestParam($paramName, $result);
            }
          } elseif (is_a($type->getName(), BaseAssertableObject::class, true))  {
            // Parametr je potomkem BaseAssertableObject
            $tmpProp = new ($type->getName())();
            $oneProp->setValue($object, $tmpProp);
            // rekurzivne zavolame mapping na property $oneProp, protoze je typu BaseAssertableObject
            $this->runMapping($tmpProp, $value);
            $result = $this->validateProperty($object, $oneProp);
            if ($result) {
              $this->invalidParams[] = new InvalidRequestParam($paramName, $result);
            }
          } else {
            // Parametr je nejaka jina trida (class, trait nebo interface), ktera neni potomkem BaseValueObject ani BaseRequestParam
          }
        } else {
          // Parametr je obycejneho PHP typu (int, string, mixed, array atd.)
          if ($type->getName() === 'array') {
            // Parametr je typu array
            if (is_array($value)) {
              // I $value je typu array. Priradime hodnotu a zkontroluejeme, podle toho, jestli ma nejake asserty
              // Podle toho, jaka je classa v Assert_IsArrayOfAssertableObjects musime vytvaret proslusne classy
              // a mapovat na ne v cyklu $value[i]
              // Pokud $oneProp nema zadny Assert, ktery by kontroloval obsah pole, muzeme pole priradit tak jak je $oneProp->setValue($object, $value);
              // Asi by nyo lepsi vytvorit metodu assignArray a v ni by se toto resilo, protoze $value muze obsahovat pole polí, polí... polí,
              // a tak se asi bude hodit volat assignArray rekuryivne pro zanorene pole.
            }

          } else {
            $oneProp->setValue($object, $value);
          }

        }



      }
    }
  }

  /**
   * @param class-string $assertableObjectClassName
   * @return BaseAssertableObject
   * @throws InvalidArgumentException
   */
  public function mapTo(string $assertableObjectClassName):BaseAssertableObject
  {

    if (!is_a($assertableObjectClassName, BaseAssertableObject::class, true)) {
      throw new InvalidArgumentException('Parameter $assertableObjectClassName has to extend from BaseAssertableObject::class');
    }

    /**
     * @var BaseAssertableObject $object
     */
    $object = new $assertableObjectClassName;

    $this->runMapping($object, $this->rawData);

    return $object;

  }

}