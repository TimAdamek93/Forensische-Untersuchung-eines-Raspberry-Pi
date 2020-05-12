<?php

// check
if ( !defined( 'MASTERANDSERVANTS' ) ) die();

// class
class Master {

  private $pdo;
  private $servants;

  public function __construct( $pdo ) {
    $this->pdo = $pdo;
    if ( $this->servantsInDb() ) {
      $this->servants = $this->selectServants();
    } else {
      echo "<p><b style='color:red;'>db is empty!</b></p>";
    }
  }

  private function servantsInDb() {
    $sql = "SELECT COUNT(*) FROM `servants`";
    try {
      $sth = $this->pdo->query( $sql );
      if ( $sth->fetchColumn() > 0 ) {
        return true;
      } else {
        return false;
      }
    }
    catch( PDOException $e ) { echo $e->getMessage(); }
  }

  private function selectServants() {
    $sql = "SELECT * FROM `servants`";
    try {
      $sth = $this->pdo->query( $sql );
      $rows = $sth->fetchAll( PDO::FETCH_ASSOC );
    }
    catch( PDOException $e ) { echo $e->getMessage(); }
    return $rows;
  }

  public function getServants() {
    return $this->servants;
  }

}

?>
