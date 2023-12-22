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
    if ($firstCahr != '/') {
      // Maska musi zacinat lomitkem.
      $value = '/'.$value;
    }
    parent::__construct($value);
  }

  public function add(VO_RouteMask $value): VO_RouteMask
  {
    $result = $this; // aby "return $result;" nerval, ze $return neni inicializovany
    try {
      // Maska vzdy zacina lomitke. viz konstruktor
      // /cesta/ + /nekam = /cesta/nekam NE /cesta//nekam, proto rtrim()
      $result = new VO_RouteMask(rtrim($this->getValueAsString(),'/') . $value->getValueAsString());
    } catch (\Throwable $e) {
      // nic. Tato vyjimka by nikdy nemela nastat, protoze spojujeme dva validni VO_RouteMask
    }
    return $result;
  }

}
