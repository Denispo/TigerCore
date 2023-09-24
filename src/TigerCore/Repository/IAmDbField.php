<?php

namespace TigerCore\Repository;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class IAmDbField {

  public function __construct(private int|string $fieldName, private int|bool|string|float $defaultValue) {
  }

  public function getFieldName():int|string {
    return $this->fieldName;
  }

  public function getDefaultValue():int|bool|string|float {
    return $this->defaultValue;
  }

}
