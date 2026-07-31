<?php
$serverName = "localhost";
$connectionInfo = array("UID" => "sa", "PWD" => "123", "Database"=>"dbcmk");
$con_dbcmk = sqlsrv_connect( $serverName, $connectionInfo);
if ( !$con_dbcmk )
{
	 echo "Connection could not be established.\n";
	 die( print_r( sqlsrv_errors(), true));
}

?>