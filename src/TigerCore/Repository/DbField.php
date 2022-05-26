<?php

namespace TigerCore\Repository;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class DbField {
  private int|string $fieldName;

  public function __construct(int|string $fieldName) {
    $this->fieldName = $fieldName;
  }

  public function getFieldName():int|string {
    return $this->fieldName;
  }

}
