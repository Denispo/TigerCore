<?php

namespace TigerCore;


interface ICanGetValueAsString {

  public function getValueAsString(string $defaultValue = ''):string;

}