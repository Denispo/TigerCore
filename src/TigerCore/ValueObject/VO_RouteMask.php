<?php

namespace TigerCore\ValueObject;

use TigerCore\ICanGetValueAsString;

class VO_RouteMask extends VO_String_Trimmed {

  public function __construct(string|ICanGetValueAsString $value) {
    if ($value instanceof ICanGetValueAsString) {
      $value = $value->getValueAsString();
    }
    $value = trim($value);
    $firstCahr = $value[0] ?? '';
    if ($firstCahr != '/' && $firstCahr != '[') {
      // Maska musi zacinat lomitkem.
      // Pokud maska nezacina lomitkem a zaroven nezacina [, pak pridame lomitko na zacatek
      $value = '/'.$value;
    }
    parent::__construct($value);
  }

}
