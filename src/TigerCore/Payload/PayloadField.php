<?php

namespace TigerCore\Repository;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class PayloadField {

  public function __construct(private string $fieldName) {
  }

  public function getFieldName():string {
    return $this->fieldName;
  }

}
