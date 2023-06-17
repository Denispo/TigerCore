<?php

namespace TigerCore\Repository;

use Nette\Database\Connection;
use Nette\Database\DriverException;
use TigerCore\ValueObject\VO_LastInsertedId;

class PDORepository {

  private Connection|null $dbConnection = null;

  public function __construct(private ICanGetDbConnection $db) {

  }


  private function getDb():Connection {
    if (!$this->dbConnection) {
      $this->dbConnection = $this->db->getDbConnection();
    }
    return $this->dbConnection;
  }


  /**
   * @param string $sql
   * @param ...$params
   * @return SqlResult
   */
  protected function select(string $sql, ...$params): SqlResult {
    return new SqlResult($this->getDb()->fetchAll($sql, ...$params));
  }

  protected function selectId(string $sql, ...$params): mixed {
    return $this->getDb()->fetchField($sql, ...$params) ?? 0;
  }

  /**
   * @param string $sql
   * @param ...$params
   * @return VO_LastInsertedId Last inserted id
   * @throws DriverException
   */
  protected function insert(string $sql, ...$params):VO_LastInsertedId {
    $db = $this->getDb();
    $db->query($sql, ...$params);
    $lastInsertedId = new VO_LastInsertedId($this->getDb()->getInsertId());
    //$db->commit(); // Throws error "there is no active transaction". Nette  or autocommit or whatever)
    return $lastInsertedId;
  }

  /**
   * @param string $sql
   * @param ...$params
   * @return int Affected rows count
   * @throws DriverException
   */
  protected function update(string $sql, ...$params):int {
    $db = $this->getDb();
    $resultset = $db->query($sql, ...$params);
    $rowsCount = $resultset->getRowCount() ?? 0;
    $db->commit();
    return $rowsCount;
  }
}