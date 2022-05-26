<?php

namespace TigerCore\Repository;

use Nette\Database\Connection;

class PDORepository {

  public function __construct(private Connection $db) {

  }



  /**
   * @param string $sql
   * @param ...$params
   * @return SqlResult
   */
  protected function select(string $sql, ...$params): SqlResult {
    return new SqlResult($this->db->fetchAll($sql, ...$params));
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