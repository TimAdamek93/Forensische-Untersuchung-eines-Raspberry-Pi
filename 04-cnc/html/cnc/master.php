<?php
session_start();

define( 'MASTERANDSERVANTS', true );

include_once( "./cnf/config.inc.php" );
include_once( "./cnf/db.inc.php" );
include_once( "./inc/functions.inc.php" );

if( $_SERVER[ "REQUEST_METHOD" ] == "GET" ) {
  makeHeader();

  if( isset( $_SESSION[ "flash" ] ) ) {
?>
      <div class="alert alert-success" role="alert">
<?php
    echo $_SESSION[ "flash" ];
    unset( $_SESSION[ "flash" ] );
?>
      </div>
<?php } ?>
      <h2>List of servants</h2>
      <table class="table table-hover">
        <thead class="thead-light">
          <tr>
            <th scope="col">ID <small>(link to loot)</small></th>
            <th scope="col">IP</th>
            <th scope="col">Heartbeat</th>
            <th scope="col">Note</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
<?php
  include_once( "./inc/master.class.php" );
  $master = new Master( $pdo );
  $now = time();

  foreach( $master->getServants() AS $servant ) {
    $seconds = $now - (int)$servant[ "heartbeat" ];
    if( $seconds < 180 ) { $color = "green"; }
    elseif( $seconds > 360 ) { $color = "red"; }
    else { $color = "orange"; }

    $dir = $config[ "lootdir" ] . $servant[ "id" ];
    if( is_dir( $dir ) ) { $link = "<a href='" . $dir . "'>" . $servant[ "id" ] . "</a>"; }
    else { $link = $servant[ "id" ];}

    //if( strpos( getSshIps(), $servant[ "address" ] ) !== false ) { $command = "close"; }
    if( strpos( getSshPorts(), $servant[ "port" ] ) !== false ) { $command = "close"; }
    else { $command = "open"; }

    echo "          <tr>\n";
    echo "            <td>" . $link . "</td>\n";
    echo "            <td>" . $servant[ "address" ] . "</td>\n";
    echo "            <td><b style='color:" . $color . ";'>" . $seconds . "</b></td>\n";
    echo "            <td><small>" . $servant[ "note" ] . "</small></td>\n";
    echo "            <td>\n";
                        makeButton( $servant, $command );
    echo "            </td>\n";
    echo "          </tr>\n";
  }
?>
        </tbody>
      </table>
<?php
    makeFooter();
}

elseif( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) {
  $commands = array( "open", "close" );
  if( isset( $_POST[ "id" ] ) && filter_var( $_POST[ "id" ], FILTER_VALIDATE_FLOAT ) ) {
    if( isset( $_POST[ "command" ] ) && in_array( $_POST[ "command" ], $commands ) ) {
      include_once( "./inc/servants.class.php" );
      $servant = new Servant( $pdo, $_POST[ "id" ] );
      $servant->updateCommand( $_POST[ "command" ] );
      $_SESSION[ "flash" ] = "Command <b>" . $_POST[ "command" ] . "</b> was successfully updated for id <b>" . $_POST[ "id" ] . "</b>.";
      header( "Location: ./master.php" );
    } else die( "Error 003\n" );
  } else die( "Error 002\n" );
} else die( "Error 001\n" );
?>
