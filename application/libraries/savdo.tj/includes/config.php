<?php
$dbhost = "localhost";
$dbuser = "younguxc_saloonuser";
$dbpass = "Younggeeks@2020";
$dbname = "younguxc_saloon";

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
//$mysqli->set_charset("utf8mb4");
$mysqli->set_charset('utf');

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$mysqli->query("SET collation_connection = utf8mb4_unicode_ci");

//Request types
define("RTYPE_CHECK", 1);
define("RTYPE_CHARGE", 2);
define("RTYPE_REDRAW", 3);
define("RTYPE_GETAGENTS", 4);

//Other settings
define("CURRENCY", "TJS");

?>