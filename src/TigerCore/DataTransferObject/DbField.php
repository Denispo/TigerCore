<?php

namespace TigerCore\DataTransferObject;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class DbField {

  public function __construct(private int|string $fieldName, private int|bool|string|float $defaultValue) {
  }

  public function getFieldName():int|string {
    return $this->fieldName;
  }

  public function getDefaultValue():int|bool|string|float {
    return $this->defaultValue;
  }

}
