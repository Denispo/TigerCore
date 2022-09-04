<?php

namespace TigerCore\Exceptions;


class CanNotWriteToFileException extends BaseFileSystemException {

  public function __construct(string $message, public string $dataWhichFailedToBeWritten = '') {
    parent::__construct($message);
  }


}