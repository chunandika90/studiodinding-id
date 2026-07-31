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
		$tsql = "select * from msstone order by m_shape,m_ukuran asc " ;
		
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
	//}
	
	//echo $tsql ;
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="20%">Shape</th>
            <th width="30%">Size</th>
            <th width="30%">Ukuran</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td onClick="selectshape('<?php echo $rowke; ?>','<?php echo $row['m_shape']; ?>','<?php echo $row['m_size']; ?>','<?php echo $row['m_ukuran']; ?>','<?php echo $row['m_hargam']; ?>','<?php echo $row['m_hargar']; ?>','<?php echo $row['m_opbm']; ?>','<?php echo $row['m_opbr']; ?>')" style="cursor:pointer"><?php echo $row['m_shape']; ?></td>
                    <td><?php echo $row['m_size']; ?></td>
                    <td><?php echo $row['m_ukuran']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>


