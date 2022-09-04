<?php

namespace TigerCore;

use TigerCore\Exceptions\CanNotCloseFileException;
use TigerCore\Exceptions\CanNotOpenFileException;
use TigerCore\Exceptions\CanNotWriteToFileException;
use TigerCore\Utils\ProxyPhpErrorHandler;

class SafeFileStream {

  private ProxyPhpErrorHandler $proxyErrorHandler;
  private string $streamPrefix = 'nette.safe://';

  public function __construct(private string $fullFileName) {
    $this->proxyErrorHandler = new ProxyPhpErrorHandler();
  }

  /**
   * @param string $data
   * @param int $lenght
   * @return int
   * @throws CanNotCloseFileException
   * @throws CanNotOpenFileException
   * @throws CanNotWriteToFileException
   */
  public function addToFile(string $data, int $lenght = -1):int {
    return $this->writeToStream_internal($data, 'a', $lenght);
  }

  /**
   * @param string $mode
   * @return resource
   * @throws CanNotOpenFileException
   */
  private function stream_open(string $mode) {
    if (!$this->proxyErrorHandler->isCapturing()) {
      $this->proxyErrorHandler->startCapturingErrors();
    }
    $handle = @fopen($this->streamPrefix.$this->fullFileName, $mode);
    if ($handle === false) {
      $errors = $this->proxyErrorHandler->stopCapturingErrors();
      throw new CanNotOpenFileException('Can not open file '.$this->fullFileName.' Reason: '.$errors[0]?->errMsg.' File: '.$errors[0]?->file.' Line: '.$errors[0]?->line);
    }
    return $handle;
  }

  /**
   * @param $stream
   * @param string $data
   * @param int $length
   * @return int
   * @throws CanNotWriteToFileException
   */
  private function stream_write($stream, string $data, int $length = -1): int{
    $writeResult = @fwrite($stream,$data,$length > 0 ? $length : null);
    if ($writeResult === false) {
      $errors = $this->proxyErrorHandler->stopCapturingErrors();
      throw new CanNotWriteToFileException('Can not write to file. Reason: '.$errors[0]?->errMsg.' File: '.$errors[0]?->file.' Line: '.$errors[0]?->line, substr($data,0,300));
    }
    return $writeResult;
  }

  /**
   * @param $stream
   * @return void
   * @throws CanNotCloseFileException
   */
  private function stream_close($stream):void {
    $result = @fclose($stream);
    $errors = $this->proxyErrorHandler->stopCapturingErrors();
    if (!$result) {
      throw new CanNotCloseFileException('Can not close opened file. Reason: '.$errors[0]?->errMsg.' File: '.$errors[0]?->file.' Line: '.$errors[0]?->line);
    }
  }

  /**
   * @param string $data
   * @param string $mode
   * @param int $length
   * @return int
   * @throws CanNotCloseFileException
   * @throws CanNotOpenFileException
   * @throws CanNotWriteToFileException
   */
  private function writeToStream_internal(string $data, string $mode = 'a', int $length = -1):int {

    if ($length === 0) {
      return 0;
    }

    // Pozor. @fopen('nette.safe://'.$fullFilePath, 'a'); zavola na pozadi SafeStream Wrapper od Nette a v nem se vola metoda stream_open, ktera taky vola svuj @fopen(.... Takze pokud v tomto Wrapperovskem @fopen dojde k chybe, zavola se nas proxyErrorHandler poprve. Ale protoze tento Wrapper byl volany diky nasemu @fopen('nette.safe://'., tak tento nas fopen taky nasledne vzhodi chybu a taky znovu  zavola proxyErrorHandler.
    // Napr. Wrapperovsky @fopen skonci chybou "Failed to open stream: Permission denied", ale tato informace se neprenese do naseho fopen, takze nas @fopen('nette.safe://'... skonci chybou "Failed to open stream: ... call failed"
    // Proto v proxyErrorHandler logujeme do arraye vsechny chyby, abychom vedeli, co se doopravdy stalo. Jinak bychom meli vzdy jen posledni chybu z naseho volani @fopen, ktera by vzdy znela "call failed", coz nam rekne prd.
    $handle = $this->stream_open($mode);
    $wasException = false;

    try {
      $result = $this->stream_write($handle, $data, $length);
    } catch (\Exception $e){
      $wasException = true;
      throw $e;
    } finally {
      // Finally se vola vzdy at uz fwrite_internal() vyhodi ci nevyhodi Exception
      try {
        $this->stream_close($handle);
      } catch (\Exception $e) {
        // pokud nam fwrite_internal() nevyhodilo Exception, tak muzeme propustit pripadnou Exception od fclose_internal().
        // Problem je, ze finally se vola vzdy a neni jina sance, jak zjistit, zda fwrite_internal() vyhodil Exception
        // Kdyby fwrite_internal() vyhodil Exception a zaroven by vyhodil exception i fclose_internal() a my bychom ji propustili, writeToFile_internal() by tak vyhodil dve Exception... ehm...  PHP no.
        if (!$wasException) {
          throw $e;
        }
      }
    }

    return $result;

  }

}