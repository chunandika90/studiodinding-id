<?php
$serverName = "DIKA-PC";
$connectionInfo = array("UID" => "sa", "PWD" => "123", "Database"=>"SI_akademik");
$con_dbnew = sqlsrv_connect( $serverName, $connectionInfo);
if ( !$con_dbnew )
{
	 echo "Connection could not be established.\n";
	 die( print_r( sqlsrv_errors(), true));
}

?>