<?php
$svrName = "DIKA-PC";
$connInfo = array("UID" => "sa", "PWD" => "123", "Database"=>"dbfinance");
$con_dbfinance = sqlsrv_connect( $svrName, $connInfo);
if ( !$con_dbfinance )
{
	 echo "Connection could not be established.\n";
	 die( print_r( sqlsrv_errors(), true));
}

?>