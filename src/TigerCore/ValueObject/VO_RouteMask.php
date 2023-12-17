<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_RouteMask extends VO_String_Trimmed {

  /**
   * @param string|ICanGetValueAsString $value
   * @throws InvalidArgumentException
   */
  public function __construct(string|ICanGetValueAsString $value) {
    if ($value instanceof ICanGetValueAsString) {
      $value = $value->getValueAsString();
    }
    $value = trim($value);
    if ($value === '') {
      throw new InvalidArgumentException('Route mask can not be empty string');
    }
    $firstCahr = $value[0] ?? '';
    if ($firstCahr != '/' && $firstCahr != '[') {
      // Maska musi zacinat lomitkem.
      // Pokud maska nezacina lomitkem a zaroven nezacina [, pak pridame lomitko na zacatek
      $value = '/'.$value;
    }
    parent::__construct($value);
  }

  public function add(VO_RouteMask $value): VO_RouteMask
  {
    $result = $this; // aby "return $result;" nerval, ze $return neni inicializovany
    try {
      $result = new VO_RouteMask($this->getValueAsString() . $value->getValueAsString());
    } catch (\Throwable $e) {
      // nic. Tato vyjimka by nikdy nemela nastat, protoze spojujeme dva validni VO_RouteMask
    }
    return $result;
  }

}
