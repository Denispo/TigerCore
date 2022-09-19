<?php

class Jedna {

  private Crypt $crypt;

  public function __construct(Crypt $crypt){
    $this->crypt = $crypt;
  }

  public function run(){
    echo(PHP_EOL.'run'.PHP_EOL);
    xdebug_debug_zval('$this->crypt');
    $this->crypt->echo('jejda');

  }

}

class Crypt {

  public function echo($text){
    echo $text;
  }

}

class Build{

  private Jedna $jedna;

  public function __construct(){
    $crypt = new Crypt();
    xdebug_debug_zval('crypt');
    $this->jedna = new Jedna($crypt);
    xdebug_debug_zval('crypt');
  }

  public function runJedna(){
    $this->jedna->run();
  }
}

$build = new Build();
$build->runJedna();