<?php

namespace TigerCore\Request\Validator;

interface ICanAssertArrayOfAssertableObjects {

  /**
   * @return class-string BaseAssertableObject class name
   */
  public function getAssertableObjectClassName(): string;

}
