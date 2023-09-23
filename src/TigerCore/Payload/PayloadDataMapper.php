<?php

namespace TigerCore\Payload;

use TigerCore\DataTransferObject\BaseDTO;
use TigerCore\DataTransferObject\ToPayloadField;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;
use TigerCore\Response\S500_InternalServerErrorException;
use TigerCore\ValueObject\BaseValueObject;

class PayloadDataMapper implements ICanGetPayloadRawData {

  private array $payload;

  /**
   * Methods directly modifies $this->payload to avoid array copying overhead
   * @template T
   * @param array<T> $data Array of classes. Each class has to be exactly the same type extended from BaseDTO class
   * @throws \ReflectionException
   */
  protected function mapFromData(array $data):void {

    $this->payload = [];

    if (!$data || count($data) === 0) {
      return;
    }

    $tmpProps = []; // [['fieldname' => 'id', 'propname' => 'userId'], [,]]


    $reflection = new \ReflectionClass(current($data));
    $props = $reflection->getProperties();

    foreach ($props as $oneProp) {
      // Prvne si ulozime vsechny PayloadField::class property...
      $attributes = $oneProp->getAttributes(ToPayloadField::class);
      foreach ($attributes as $oneAttribute) {

        /**
         * @var ToPayloadField $attr
         */
        $attr = $oneAttribute->newInstance();
        $fieldName = $attr->getFieldName();
        if ($fieldName === '') {
          $fieldName = $oneProp->getName();
        }
        if ($oneProp->isPublic()) {
          $propParams = ['fieldname' => $fieldName, 'propname' => $oneProp->name, 'is_vo' => false, 'allows_null' => false];

          $type = $oneProp->getType();

          if ($type && !$type->isBuiltin()) {
            if (is_a($type->getName(), BaseValueObject::class, true)) {
              // Parametr je BaseValueObject
              $propParams['is_vo'] = true;
            } else {
              // Parametr je nejaka jina trida (class, trait nebo interface), ktera neni potomkem BaseValueObject
            }
            if ($type->allowsNull()) {
              $propParams['allows_null'] = true;
            }
          }
          $tmpProps[] = $propParams;
        } else {
          // TODO: Reagovat, ze property neni public?
        }

      }
    }


    foreach ($data as $oneData) {
      foreach ($tmpProps as $oneTmpProp) {
        if ($oneTmpProp['allows_null'] && $oneData->{$oneTmpProp['propname']} === null) {
          // If property allows to be null (ie: string|null) and value is null, just set null and go
          $this->payload[$oneTmpProp['fieldname']] = null;
        } else {
          if ($oneTmpProp['is_vo']) {
            $vo = $oneData->{$oneTmpProp['propname']};
            $this->payload[$oneTmpProp['fieldname']] = $vo instanceof ICanGetValueAsString ? $vo->getValueAsString() : ($vo instanceof ICanGetValueAsInit ? $vo->getValueAsInt() : '' /*TODO: co s tim, kdyz nelze ziskat ani integer ani string?*/);
          } else {
            $this->payload[$oneTmpProp['fieldname']] = $oneData->{$oneTmpProp['propname']};
          }
        }
      }
    }
  }

  /**
   * If $data is array of the same classes extended from BaseDTO, automatic data mapping will be performed on each #[ToPayloadField] public property. Othervise $data will be considered as final array of raw payload data.
   * @param BaseDTO|BaseDTO[] $data
   * @throws S500_InternalServerErrorException|InvalidArgumentException
   */
  public function __construct(array|BaseDTO $data = []) {
    $this->payload = [];
    if (!is_array($data)) {
      $data = [$data];
    }
    foreach ($data as $oneData) {
      if (!($oneData instanceof BaseDTO)) {
        throw new InvalidArgumentException('All prams has to be instance of BaseDTO');
      }
    }

    try {
      $this->mapFromData($data);
    } catch (\ReflectionException $e){
      throw new S500_InternalServerErrorException('Reflection exception. Can not map data to payload',['data' => var_export($data, true)]);
    }
  }

  public function getPayloadRawData(): array
  {
    return $this->payload;
  }
}