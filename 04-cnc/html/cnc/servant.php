<?php
define( 'MASTERANDSERVANTS', true );

if( $_SERVER[ "REQUEST_METHOD" ] == "GET" ) {
  if( isset( $_GET[ "id" ] ) && filter_var( $_GET[ "id" ], FILTER_VALIDATE_FLOAT ) ) {
    include_once( "./cnf/db.inc.php" );
    include_once( "./inc/servants.class.php" );
    $servant = new Servant( $pdo, $_GET[ "id" ] );
    if ( !empty( $servant->getCommand() ) ) {
      echo $servant->getCommand() . ":" . $servant->getPort();
    }
    $servant->updateServant();
  } else die( "Error 001\n" );
} else die( "Error 002\n" );
?>
