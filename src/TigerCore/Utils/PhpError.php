<?php

namespace TigerCore\Utils;

class PhpError {

  public function __construct(
    public int $errNo,
    public string $errMsg,
    public string $file,
    public int $line
  )
  {}


}