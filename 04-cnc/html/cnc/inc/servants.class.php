<?php

// check
if ( !defined( 'MASTERANDSERVANTS' ) ) die();

// class
class Servant {

  private $pdo;
  private $servant;

  public function __construct( $pdo, $id ) {
    $this->pdo = $pdo;
    if ( $this->servantIsNotInDb( $id ) ) {
      $this->insertServant( $id );
    }
    $this->servant = $this->selectServant( $id );
  }

  private function servantIsNotInDb( $id ) {
    $sql = "SELECT COUNT(*) FROM * `servants` WHERE id=:id";
    try {
      $sth = $this->pdo->prepare( $sql );
      //$sth->bindParam( ':id', $id, PDO::PARAM_INT ); // is not working with bigint!
      $sth->bindParam( ':id', $id );
      $sth->execute();
      if ( $sth->fetchColumn() == 0 ) {
        return true;
      } else {
        return false;
      }
    }
    catch( PDOException $e ) { echo $e->getMessage(); }
  }

  private function insertServant( $id ) {
    $sql = "INSERT INTO `servants` ( id, address, heartbeat ) VALUES ( :id, :address, :heartbeat )";
    $this->heartbeat = time();
    try {
      $sth = $this->pdo->prepare( $sql );
      //$sth->bindParam( ':id', $id, PDO::PARAM_INT ); // is not working with bigint!
      $sth->bindParam( ':id', $id );
      $sth->bindParam( ':address', $this->servant[ "address" ], PDO::PARAM_STR );
      $sth->bindParam( ':heartbeat', $this->heartbeat, PDO::PARAM_INT );
      $sth->execute();
    }
      catch( PDOException $e ) { echo $e->getMessage(); }
  }

  private function selectServant( $id ) {
    $sql = "SELECT * FROM `servants` WHERE id=:id";
    try {
      $sth = $this->pdo->prepare( $sql );
      // $sth->bindParam( ':id', $id, PDO::PARAM_INT ); // is not working with bigint!
      $sth->bindParam( ':id', $id );
      $sth->execute();
      $row = $sth->fetchAll( PDO::FETCH_ASSOC )[ 0 ];
    }
    catch( PDOException $e ) { echo $e->getMessage(); }
    return $row;
  }

  public function getCommand() {
    return $this->servant[ "command" ];
  }

  public function getPort() {
    return $this->servant[ "port" ];
  }

  public function getServant() {
    return $this->servant;
  }

  public function updateCommand( $command ) {
    $sql = "UPDATE `servants` SET command=:command WHERE id=:id";
    try {
      $sth = $this->pdo->prepare( $sql );
      //$sth->bindParam( ':id', $this->servant[ "id" ], PDO::PARAM_INT ); // is not working for bigint!
      $sth->bindParam( ':id', $this->servant[ "id" ] );
      $sth->bindParam( ':command', $command, PDO::PARAM_STR );
      $sth->execute();
    }
    catch( PDOException $e ) { echo $e->getMessage(); }
  }

  public function updateServant() {
    $this->servant[ "address" ] = filter_var( $_SERVER["REMOTE_ADDR"], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 );
    $this->servant[ "heartbeat" ] = time();
    $this->servant[ "command" ] = "";
    $sql = "UPDATE `servants` SET address=:address, heartbeat=:heartbeat, command=:command WHERE id=:id";
    try {
      $sth = $this->pdo->prepare( $sql );
      //$sth->bindParam( ':id', $this->servant[ "id" ], PDO::PARAM_INT ); // is not working for bigint!
      $sth->bindParam( ':id', $this->servant[ "id" ] );
      $sth->bindParam( ':address', $this->servant[ "address" ], PDO::PARAM_STR );
      $sth->bindParam( ':heartbeat', $this->servant[ "heartbeat" ], PDO::PARAM_INT );
      $sth->bindParam( ':command', $this->servant[ "command" ], PDO::PARAM_STR );
      $sth->execute();
    }
    catch( PDOException $e ) { echo $e->getMessage(); }
  }

}

?>
