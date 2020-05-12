<?php

// check
if ( !defined( 'MASTERANDSERVANTS' ) ) die();

// config
$drv = "mysql";
$host= "localhost";
$db = "cnc";
// user und pass bitte setzen:
$user = "";
$pass = "";

try { $pdo = new PDO( $drv . ":host=" . $host . ";dbname=" . $db, $user, $pass ); }
catch( PDOException $e ) { echo $e->getMessage(); }

?>
