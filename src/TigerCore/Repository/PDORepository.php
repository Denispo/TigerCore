<?php

namespace TigerCore\Repository;

use Nette\Database\Connection;
use TigerCore\ValueObject\VO_LastInsertedId;

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
   * @return VO_LastInsertedId Last inserted id
   */
  protected function insert(string $sql, ...$params):VO_LastInsertedId {
    $this->db->query($sql, ...$params);
    $lastInsertedId = new VO_LastInsertedId($this->db->getInsertId());
    $this->db->commit();
    return $lastInsertedId;
  }
}