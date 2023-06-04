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

}
