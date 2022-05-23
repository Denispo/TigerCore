<?php

namespace TigerCore\Constants;



use TigerCore\Exceptions\InvalidArgumentException;


abstract class BaseConst {

  private int $value;

  private array $consts;

  private function constExists(int $value):bool {
    if (!$this->consts){
      try {
        $reflectionClass = new \ReflectionClass($this);
        $this->consts = $reflectionClass->getConstants();
      } catch (\ReflectionException $e) {
        // TODO: Reagovat na vyjimku. Tato by ale nemela nikdy nastat.
      }
    }
    return in_array($value, $this->consts);
  }

  /**
   * @param $value
   * @throws InvalidArgumentException
   */
  private function ThrowDoNotExists($value) {
    if (is_bool($value)) $value = $value ? 'true' : 'false';
    throw new InvalidArgumentException('Constant with value: '.$value.' is not defined in class: '.get_class($this));
  }

  /**
   * @param $value
   * @throws InvalidArgumentException
   */
  private function setValue($value):void {
    if ($this->constExists($value)) {
      $this->value = $value;
    } else {
      $this->ThrowDoNotExists($value);
    }
  }

  protected function getValue():int {
    return $this->value;
  }

  /**
   * BaseConst constructor.
   * @param int $value
   * @throws InvalidArgumentException
   */
  public function __construct(int $value) {
    $this->consts = [];
    $this->setValue($value);
  }

  /**
   * @param int|null $value
   * @return bool
   */
  protected function IsSetToValue(int|null $value):bool {
    if ($value === null) {
      return false;
    }
    if (!$this->constExists($value)) {
      //TODO: Logovat, ze konstanta neexistuje
    }
    return $this->value == $value;
  }

}
