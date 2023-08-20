<?php

namespace TigerCore;


interface IAmMappedValueObject extends ICanGetValueAsString, ICanGetValueAsInit {

  public function __construct(int|string|ICanGetValueAsInit|ICanGetValueAsString $value);

}