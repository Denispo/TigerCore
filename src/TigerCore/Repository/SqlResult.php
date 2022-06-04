<?php

namespace TigerCore\Repository;

use Nette\Utils\DateTime;
use ReflectionException;
use TigerCore\ValueObject\BaseValueObject;

class SqlResult {

  /**
   * @param int[][]|string[][]|DateTime[][]|float[][]|bool[][]|\DateInterval[][] $data
   */
  public function __construct(private array $data) {

  }

  /**
   * @template T of BaseDbData
   * @param BaseDbData<T> $dbData
   * @return array<BaseDbData<T>>
   * @throws ReflectionException
   */
  public function mapToData(BaseDbData $dbData):array {

    $tmpProps = []; // [['field' => number, 'propname' => string], [,]]
    $result = [];


    $reflection = new \ReflectionClass($dbData);
    $props = $reflection->getProperties();

    foreach ($props as $oneProp) {
      // Prvne si ulozime vsechny DbField::class property...
      $attributes = $oneProp->getAttributes(DbField::class);
      foreach ($attributes as $oneAttribute) {

        /**
         * @var DbField $attr
         */
        $attr = $oneAttribute->newInstance();
        $fieldName = $attr->getFieldName();
        if ($oneProp->isPublic()) {
          $propParams = ['field' => $fieldName, 'propname' => $oneProp->name, 'is_vo' => false, 'vo_classname' => ''];
          $type = $oneProp->getType();

          if ($type && !$type->isBuiltin()) {
            if (is_a($type->getName(), BaseValueObject::class, true)) {
              // Parametr je BaseValueObject
              $propParams['is_vo'] = true;
              $propParams['vo_classname'] = $type->getName();
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

    // ... a pak vytvorime prislusne objekty.
    foreach ($this->data as $oneData) {
      $obj = new $dbData();
      foreach ($tmpProps as $oneTmpProp) {
        if (array_key_exists($oneTmpProp['field'], $oneData)) {
          if ($oneTmpProp['is_vo']) {
            $obj->$oneTmpProp['propname'] = (new $oneTmpProp['vo_classname'])($oneData[$oneTmpProp['field']]);
          } else {
            $obj->$oneTmpProp['propname'] = $oneData[$oneTmpProp['field']];
          }
        } else {
          // TODO: nekde ulozit/nekoho informovat, ze pro tuto property nemame z DB informaci
        }
      }
      $result[] = $obj;
    }

    return $result;

  }

}