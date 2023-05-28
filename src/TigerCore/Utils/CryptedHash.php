<?php

namespace TigerApi\Auth;

use TigerCore\Exceptions\ExpiredException;
use TigerCore\Exceptions\InvalidArgumentException;
use TigerCore\ICanGetValueAsInit;
use TigerCore\ICanGetValueAsString;
use TigerCore\Utils\Crypt;
use TigerCore\ValueObject\VO_Duration;
use TigerCore\ValueObject\VO_Hash;

class CryptedHash {

  public function __construct(private string $passphrase) {

  }

  /**
   * @param ICanGetValueAsString|ICanGetValueAsInit $value
   * @return VO_Hash
   * @throws \Exception
   */
  public function generateHash(string|int|ICanGetValueAsString|ICanGetValueAsInit $value):VO_Hash {
    if ($value instanceof ICanGetValueAsString){
      $value = $value->getValueAsString();
    } elseif ($value instanceof ICanGetValueAsInit){
      $value = $value->getValueAsInt();
    }

    // Time is rouded to 5 minute granularity (to save some bytes)
    $timestampPacked = pack('V',round(ceil(time() / 60*5)));

    // $timestampPacked is 4 Bytes long there. We can remove first byte, because it is always 0 (because time() / (60*5))
    $timestampPacked = substr($timestampPacked, 1);

    if (is_int($value)) {
      $userIdPacked = pack('l', $value); // 32 bit unsigned integer
    } else {
      $userIdPacked = $value;
      if (strlen($userIdPacked) === 4) {
        // Length of $userIdPacked can not be 4 bytes. 4 bytes length is "reserved" for integer version of UserId. Because pack('l', $userId); always returns 4 bytes length string.
        // If length of $userIdPacked is accidentaly 4 bytes, we have to add one additional byte to recognize that $userIdPacked is string.
        $userIdPacked = $userIdPacked . "\x00";
      }
    }
    $hash = Crypt::encode($timestampPacked.$userIdPacked, $this->passphrase);
    return new VO_Hash($hash);
  }

  /**
   * @param VO_Hash $hash
   * @param VO_Duration $hashDuration
   * @return string|int
   * @throws InvalidArgumentException|ExpiredException|\Exception
   */
  public function getValueFromHash(VO_Hash $hash, VO_Duration $hashDuration):string|int {
    if ($hash->isEmpty()) {
      throw new InvalidArgumentException();
    }
    $binary = Crypt::decode($hash->getValueAsString(), $this->passphrase);
    if (strlen($binary) < 4) {
      // 3 bytes timestamp + 1 byte (at least) UserId
      throw new InvalidArgumentException();
    }
    $timestamp = current(unpack('V',"\x00".substr($binary,0,3))) * (60*5);

    if (($timestamp + $hashDuration->getValueAsInt() < time()) || ($timestamp - (5*60) > time())) {
      // $timestamp - (5*60) can not be bigger than time()
      throw new ExpiredException();
    }

    $value = substr($binary, 3);

    if (strlen($value) === 4) {
      // $value is an 32 bit integer
      $value = current(unpack('l', $value));
    } else {
      // $value is a string
      $value = rtrim($value,"\x00");
    }

    return $value;

  }

}