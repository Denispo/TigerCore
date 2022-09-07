<?php

namespace TigerCore\Repository;

use Nette\Database\Row;
use TigerCore\DataTransferObject\BaseDTO;
use TigerCore\DataTransferObject\FromDbField;
use TigerCore\ValueObject\BaseValueObject;
use TigerCore\ValueObject\VO_DbFieldName;

class SqlResult {

  /**
   * --param int[][]|string[][]|DateTime[][]|float[][]|bool[][]|\DateInterval[][] $data
   * @param Row[] $data
   */
  public function __construct(private array $data) {

  }

  /**
   * @param VO_DbFieldName $fieldName
   * @return int[]|string[]|float[]|bool[]|\DateInterval[]
   */
  public function getByFieldName(VO_DbFieldName $fieldName):array {
    if (!$this->data) {
      return [];
    }
    $result = [];
    if (!$fieldName->isValid() || !property_exists(current($this->data), $fieldName->getValue())) {
      return $result;
    }

    foreach ($this->data as $oneData) {
      $result[] = $oneData[$fieldName->getValue()];
    }
    return $result;

  }
  
  /**
   * @template T of BaseDbData
   * @param BaseDTO<T> $dbData
   * @return array<BaseDTO<T>>
   */
  public function mapToData(BaseDTO $dbData):array {

    if (!$this->data) {
      // pokud nejsou data, tak si hrajeme an to, ze data jsou prazny objekt. Diky tomu se vsechny property u T inicializuji a nastavi se jim defaultni hodnota
      $this->data = [new BaseDTO()];
    }

    $tmpProps = []; // [['field' => 'id', 'propname' => 'userId'], [,]]
    $result = [];


    $reflection = new \ReflectionClass($dbData);
    $props = $reflection->getProperties();

    $tempData = current($this->data);

    foreach ($props as $oneProp) {
      // Prvne si ulozime vsechny DbField::class property...
      $attributes = $oneProp->getAttributes(FromDbField::class);
      foreach ($attributes as $oneAttribute) {

        /**
         * @var FromDbField $attr
         */
        $attr = $oneAttribute->newInstance();
        $fieldName = $attr->getFieldName();
        if ($oneProp->isPublic()) {
          $propParams = ['field' => $fieldName, 'default'=> $attr->getDefaultValue() , 'propname' => $oneProp->name, 'is_vo' => false, 'vo_classname' => ''];
          $propParams['exists'] = property_exists($tempData, $fieldName);



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
        if ($oneTmpProp['exists']) {

          if ($oneTmpProp['is_vo']) {
            $obj->{$oneTmpProp['propname']} = new $oneTmpProp['vo_classname']($oneData[$oneTmpProp['field']] ?? $oneTmpProp['default']);
          } else {
            $obj->{$oneTmpProp['propname']} = $oneData[$oneTmpProp['field']] ?? $oneTmpProp['default'];
          }

        } else {
          // TODO: nekde ulozit/nekoho informovat, ze pro tuto property nemame z DB informaci
          if ($oneTmpProp['is_vo']) {
            $obj->{$oneTmpProp['propname']} = new $oneTmpProp['vo_classname']($oneTmpProp['default']);
          } else {
            $obj->{$oneTmpProp['propname']} = $oneTmpProp['default'];
          }
        }
      }
      $result[] = $obj;
    }

    return $result;

  }

}