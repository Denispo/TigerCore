<?php

namespace TigerCore\Repository;

use Nette\Database\Connection;
use Nette\Utils\DateTime;

class PDORepository {

  public function __construct(private Connection $db) {

  }

  /**
   * @param string $sql
   * @param ...$params
   * @return int[][]|string[][]|DateTime[][]|float[][]|bool[][]|\DateInterval[][]
   */
  protected function fetchAll(string $sql, ...$params): array {
    return $this->db->fetchAll($sql, ...$params);
  }

  /**
   * @param string $sql
   * @param ...$params
   * @return string Last inserted id
   */
  protected function insert(string $sql, ...$params):string {
    $this->db->query($sql, ...$params);
    return $this->db->getInsertId();
  }
}