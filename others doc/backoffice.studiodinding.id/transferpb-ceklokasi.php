<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$sctx = $_GET['tx'];

	//if ( $sctx != '' )
	//{
		$tsql = "select a.m_kode, a.m_nama from mslokasi a 
				 where (m_kode like '%ANDRE%' or m_kode like '%PUSAT-SK%')    " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
	//}
	
	//echo $tsql ;
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="20%">Kode</th>
            <th width="30%">Nama</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td onClick="selectlokasi('<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>')" style="cursor:pointer"><?php echo $row['m_kode']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>


