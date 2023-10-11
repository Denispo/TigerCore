<?php

namespace TigerCore\Repository;

use Nette\Database\Row;
use TigerCore\ICanConstructMyself;
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
    if (!property_exists(current($this->data), $fieldName->getValueAsString())) {
      return $result;
    }

    foreach ($this->data as $oneData) {
      $result[] = $oneData[$fieldName->getValueAsString()];
    }
    return $result;

  }

  /**
   * @template T
   * @param ICanConstructMyself<T> $resultClassTemplate
   * @param array $orderMapValues
   * @param VO_DbFieldName|null $orderFieldName
   * @return array<ICanConstructMyself<T>>
   *
   * $orderMapValues obsahuje hodnoty Fieldu $orderFieldName v tom poradi v jakem je chceme mit ve vyslednem poli.
   * Napr. $this->data ma polozky "id" [1,2,5,10], ale my je chceme v poradi [5,1,2,10], tak dame do $orderMapValues hodnoty [5,1,2,10] a do $orderFieldName dame hodnotu "id"
   */
  public function mapToClass(ICanConstructMyself $resultClassTemplate, array $orderMapValues = [], VO_DbFieldName|null $orderFieldName = null):array {

    if (!$this->data) {
      // pokud nejsou data, tak si hrajeme an to, ze data jsou prazny objekt. Diky tomu se vsechny property u T inicializuji a nastavi se jim defaultni hodnota
      $this->data = [$resultClassTemplate::construct()];
    }

    $orderMapKeys = [];

    // hodnoty $orderMapValues se stanou Klici a hodnota $orderMapKeys[Klic] je potom index na jakem ma byt dany zaznam v $result.
    foreach ($orderMapValues as $index => $value){
      // Pokud je v $orderMapValues vicekrat stejna Hodnota, $orderMapKeys[Hodnota] se prepise a tim padem bude count($orderMapKeys) mensi, nez je count($orderMapValues).
      $orderMapKeys[$value] = $index;
    }


    $orderByMap = false;
    if (count($this->data) == count($orderMapKeys) && $orderFieldName) {
      // Pocet radku "$this->data" musi odpovidat poctu polozek v $orderMapKeys
      $orderByMap = true;
    }

    $tmpProps = []; // [['field' => 'id', 'propname' => 'userId'], [,]]
    $result = [];


    $reflection = new \ReflectionClass($resultClassTemplate);
    $props = $reflection->getProperties();

    $tempData = current($this->data);

    foreach ($props as $oneProp) {
      // Prvne si ulozime vsechny DbField::class property...
      $attributes = $oneProp->getAttributes(IAmDbField::class);
      foreach ($attributes as $oneAttribute) {

        /**
         * @var IAmDbField $attr
         */
        $attr = $oneAttribute->newInstance();
        $fieldName = $attr->getFieldName();
        if ($oneProp->isPublic()) {
          $propParams = ['field' => $fieldName, 'default'=> $attr->getDefaultValue() , 'propname' => $oneProp->name, 'is_vo' => false, 'vo_classname' => '', 'allows_null' => false];
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

    // ... a pak vytvorime prislusne objekty.
    foreach ($this->data as $oneData) {
      $obj = new ($resultClassTemplate::class)();
      if ($orderByMap) {
        // Natvrdo predpokladame, ze vsechny $this->data maji field s nazvem $orderFieldName->getValueAsString()
        // TODO: asi by se tento prdpoklad mel nejak osetrit?
        $resultIndex = $orderMapKeys[$oneData[$orderFieldName->getValueAsString()]];
      }
      foreach ($tmpProps as $oneTmpProp) {
        if ($oneTmpProp['exists']) {

          if ($oneTmpProp['allows_null'] && $oneData[$oneTmpProp['field']] === null) {
            // pokud DB vratila null a zaroven je $oneTmpProp i typu null (napr VO_BaseId|null), ulozime null a dal neresime
            $obj->{$oneTmpProp['propname']} = null;
          } else {
            if ($oneTmpProp['is_vo']) {
              $obj->{$oneTmpProp['propname']} = new $oneTmpProp['vo_classname']($oneData[$oneTmpProp['field']] ?? $oneTmpProp['default']);
            } else {
              $obj->{$oneTmpProp['propname']} = $oneData[$oneTmpProp['field']] ?? $oneTmpProp['default'];
            }
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
      if ($orderByMap){
        // Polozky pridame na pozice podle $orderMapValues
        $result[$resultIndex] = $obj;
      } else {
        // Polozky pridame jednu za druhou tak, jak je zpracovavame
        $result[] = $obj;
      }


    }

    return $result;

  }

}