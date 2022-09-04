<?php

namespace TigerCore\Exceptions;


class CanNotCloseFileException extends BaseFileSystemException {

  public function __construct(string $message) {
    parent::__construct($message);
  }


}