<?php

namespace TigerCore\Utils;


class ProxyPhpErrorHandler{

  private string|null $oldErrorHandler = null;
  private bool $isCapturing = false;

  /**
   * @var PhpError[]
   */
  private array $errors = [];

  public function __construct() {

  }

  public function __destruct() {
    if ($this->oldErrorHandler) {
      set_error_handler($this->oldErrorHandler);
    }
  }

  public function isCapturing():bool {
    return $this->isCapturing;
  }

  private function handler(int $errNo, string $errMsg, string $file, int $line):void {
    $this->errors[] = new PhpError($errNo, $errMsg, $file, $line);
  }

  public function startCapturingErrors():void {
    $this->isCapturing = true;
    $this->oldErrorHandler = set_error_handler(\Closure::fromCallable([$this,'handler']));
  }

  /**
   * @return PhpError[]
   */
  public function stopCapturingErrors():array {
    if ($this->oldErrorHandler) {
      $this->isCapturing = false;
      set_error_handler($this->oldErrorHandler);
    }
    return $this->errors;
  }
  
}