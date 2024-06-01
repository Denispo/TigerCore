<?php
declare(strict_types=1);
namespace TigerCore\ValueObject;

use Nette\Utils\Strings;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsString;

class VO_UrlSlug extends VO_String_Trimmed {

  /**
   * @throws InvalidArgumentException
   */
  public static function createFromString(string|ICanGetValueAsString $textToBeSlugged): self
  {
    if ($textToBeSlugged instanceof ICanGetValueAsString) {
      $textToBeSlugged = $textToBeSlugged->getValueAsString();
    }
    if (trim($textToBeSlugged) === '') {
      throw new InvalidArgumentException('$textToBeSlugged can not be empty string');
    }
    $sluggedText = Strings::webalize($textToBeSlugged);
    if ($sluggedText === '') {
      $sluggedText = 'slg-'.strtolower(substr(md5($textToBeSlugged),0,8));
    }
    return new self(Strings::webalize($sluggedText));
  }

  /**
   * @param string $sluggedString
   * @throws InvalidArgumentException
   */
  public function __construct(string $sluggedString) {
    parent::__construct($sluggedString);

    if ($this->getValueAsString() !== urlencode($this->getValueAsString())) {
      throw new InvalidArgumentException('UrlSlug contains invalid character', ['$sluggedString' => $sluggedString]);
    }

  }

}