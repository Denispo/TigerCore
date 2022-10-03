<?php

namespace TigerCore;

interface ICanValidateFloat {

  public function isFloatValid(float|ICanGetValueAsFloat $value):bool;

}
