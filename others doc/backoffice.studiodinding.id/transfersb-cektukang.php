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
		$tsql = "select a.m_kode, a.m_nama , b.m_nama as lokasi
				 from mstukang a 
				 left join mslokasi b on a.m_lokasi = b.m_kode
				 where a.m_nama like '%".$sctx."%'" ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
	//}
	
	//echo $tsql ;
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="20%">Kode</th>
            <th width="30%">Nama</th>
            <th width="30%">Lokasi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td onClick="selectukang('<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>')" style="cursor:pointer"><?php echo $row['m_kode']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                    <td><?php echo $row['lokasi']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>


