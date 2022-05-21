<?php

namespace Core\ValueObject;


abstract class BaseValueObject {

    protected mixed $value;

    public function __construct(mixed $value) {
        $this->value = $value;
    }

    abstract function getValue():mixed;

    abstract function isValid():bool;

    abstract function isEmpty():bool;

}
