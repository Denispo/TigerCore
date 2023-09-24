<?php

namespace TigerCore\Payload;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class IAmPayloadField {

  private string $fieldName;

  public function __construct(string $fieldName = '') {
    $this->fieldName = trim($fieldName);
  }

  public function getFieldName():string {
    return $this->fieldName;
  }

}
