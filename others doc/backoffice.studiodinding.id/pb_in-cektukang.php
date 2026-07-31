<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$rowke = $_GET['rk'];

	//if ( $sctx != '' )
	//{
		$tsql = "select m_nama from mstukang" ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
	//}
	
	//echo $tsql ;
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="20%">Type</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td onClick="selecttukang('<?php echo $rowke; ?>','<?php echo $row['m_nama']; ?>')" style="cursor:pointer"><?php echo $row['m_nama']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>


