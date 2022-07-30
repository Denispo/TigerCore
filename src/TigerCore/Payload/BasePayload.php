<?php

namespace TigerCore\Payload;

use TigerCore\DataTransferObject\PayloadField;
use TigerCore\ValueObject\BaseValueObject;

abstract class BasePayload implements IBasePayload{

  /**
   * @template T
   * @param array<T> $data Array of classes. Each class has to be exactly the same type
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
      $attributes = $oneProp->getAttributes(PayloadField::class);
      foreach ($attributes as $oneAttribute) {

        /**
         * @var PayloadField $attr
         */
        $attr = $oneAttribute->newInstance();
        $fieldName = $attr->getFieldName();
        if ($oneProp->isPublic()) {
          $propParams = ['fieldname' => $fieldName, 'propname' => $oneProp->name, 'is_vo' => false];

          $type = $oneProp->getType();

          if ($type && !$type->isBuiltin()) {
            if (is_a($type->getName(), BaseValueObject::class, true)) {
              // Parametr je BaseValueObject
              $propParams['is_vo'] = true;
            } else {
              // Parametr je nejaka jina trida (class, trait nebo interface), ktera neni potomkem BaseValueObject
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
          $res[$oneTmpProp['fieldname']] = $oneData->{$oneTmpProp['propname']}->getValue();
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
   * @template T
   * @param array|array<T> $data
   * @param bool $mapFromDbData
   * @throws \ReflectionException
   */
  public function __construct(array $data, bool $mapFromDbData) {
    if ($mapFromDbData) {
      $this->payload = $this->mapFromData($data);
    } else {
      $this->payload = $data;
    }
  }

  public function getPayloadData():array {
    return $this->payload;
  }

}