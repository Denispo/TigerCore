<?php

namespace TigerCore\ValueObject;

use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_TokenPlainStr extends VO_String_Trimmed {

  public static function createFromBearerRequest():self|null {

    $authToken = '';
    // https://github.com/yiisoft/yii2/issues/13564
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
      $authToken = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
      $authToken = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    }

    $str = explode(' ', $authToken,2);
    if ($str && $str[0] == 'Bearer') {
      $str = $str[1] ?? '';
    } else {
      $str = '';
    }
    try {
      return new self($str);
    } catch (InvalidArgumentException $e) {
      return null;
    }
  }

  /**
   * @throws InvalidArgumentException
   */
  public function __construct(ICanGetValueAsString|string $value)
  {
    parent::__construct($value);
    if ($this->isEmpty()) {
      throw new InvalidArgumentException('Token string can not be empty');
    }
  }

}
