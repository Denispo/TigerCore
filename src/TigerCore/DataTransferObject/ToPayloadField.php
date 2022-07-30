<?php

namespace TigerCore\DataTransferObject;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ToPayloadField {

  public function __construct(private string $fieldName) {
  }

  public function getFieldName():string {
    return $this->fieldName;
  }

}
