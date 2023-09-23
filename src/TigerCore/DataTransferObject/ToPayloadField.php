<?php

namespace TigerCore\DataTransferObject;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ToPayloadField {

  private string $fieldName;

  public function __construct(string $fieldName = '') {
    $this->fieldName = trim($fieldName);
  }

  public function getFieldName():string {
    return $this->fieldName;
  }

}
