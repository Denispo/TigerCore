<?php

use Tester\Assert;
use TigerCore\Utils\Crypt;
use TigerCore\ValueObject\VO_PasswordPlainText;

require_once __DIR__.'/bootstrap.php';

test('Crypt can decode encoded text',function(){
  $text = "i:2012,h:'MayhnMk45445ad'";
  $password = new VO_PasswordPlainText('SuperPassword');
  $encodedAndDecoded = Crypt::decode(Crypt::encode($text, $password),$password);
  Assert::same($text,$encodedAndDecoded);
});
