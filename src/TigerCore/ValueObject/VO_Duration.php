<?php

namespace TigerCore\ValueObject;


class VO_Duration extends VO_Int {

  public function __construct(int $seconds = 0, int $minutes = 0, int $hours = 0, int $days = 0) {
    parent::__construct($seconds + ($minutes * 60) + ($hours * 60 * 60) + ($days * 24 * 60 * 60));

  }

  public function addMinutes(int $minutes): self {
    return new self($this->getValueAsInt()+($minutes*60));
  }

  public function addHours(int $hours): self {
    return new self($this->getValueAsInt()+($hours*60*60));
  }

  public function addDays(int $days): self {
    return new self($this->getValueAsInt()+($days*24*60*60));
  }

}
