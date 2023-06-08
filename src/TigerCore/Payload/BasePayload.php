<?php

namespace TigerCore\Payload;

use TigerCore\DataTransferObject\BaseDTO;
use TigerCore\DataTransferObject\ToPayloadField;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;
use TigerCore\Response\S500_InternalServerErrorException;
use TigerCore\ValueObject\BaseValueObject;

abstract class BasePayload implements ICanGetPayloadRawData{

  /**
   * @template T
   * @param array<T> $data Array of classes. Each class has to be exactly the same type extended from BaseDTO class
   * @return array Payload data mapped from $data object
   * @throws \ReflectionException
   */
  protected function mapFromData(array $data):array {

    if (!$data || count($data) === 0) {
      return [];
    }

    $tmpProps = []; // [['fieldname' => 'id', 'propname' => 'userId'], [,]]
    $result = [];


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
              // Zatim se nikde nepouziva
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
      $res = [];
      foreach ($tmpProps as $oneTmpProp) {
        if ($oneTmpProp['is_vo']) {
          $vo = $oneData->{$oneTmpProp['propname']};
          $res[$oneTmpProp['fieldname']] = $vo instanceof ICanGetValueAsString ? $vo->getValueAsString() : ($vo instanceof ICanGetValueAsInit ? $vo->getValueAsInt() : '' /*TODO: co s tim, kdyz nelze ziskat ani integer ani string?*/);
        } else {
          $res[$oneTmpProp['fieldname']] = $oneData->{$oneTmpProp['propname']};
        }
      }
      $result[] = $res;
    }

    return $result;

  }

  private array $payload;

  /**
   * If $data is array of the same classes extended from BaseDTO, automatic data mapping will be performed on each #[ToPayloadField] public property. Othervise $data will be considered as final array of raw payload data.
   * @param array|BaseDTO[] $data
   * @throws S500_InternalServerErrorException
   */
  public function __construct(array $data = []) {
    $mapFromDbData = false;
    foreach ($data as $oneData) {
      $mapFromDbData = $oneData instanceof BaseDTO;
      if (!$mapFromDbData) {
        break;
      }
    }
    if ($mapFromDbData) {
      try {
        $this->payload = $this->mapFromData($data);
      } catch (\ReflectionException $e){
        throw new S500_InternalServerErrorException('Reflection exception. Can not map data to payload',['data' => var_export($data, true)]);
      }
    } else {
      $this->payload = $data;
    }
  }

  public function getPayloadRawData():array {
    return $this->payload;
  }

}