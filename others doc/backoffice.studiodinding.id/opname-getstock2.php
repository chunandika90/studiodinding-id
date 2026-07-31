<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$kdstore = $_GET['cb'];
	$periode = $_GET['pr'];
	$prm = $_GET['prm'];

	
	$dumb = explode('-',$periode);
	
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tanggal,108) as co_jam from t_stockopname0 a where a.m_cabang = '".$kdstore."' and year(a.m_tanggal) = ".$dumb[0]." and month(a.m_tanggal) = ".$dumb[1]." order by a.m_nomor desc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="4"><h4>GET DATA STOCK - OPNAME</h4></th>
        </tr>
        <tr>
            <th width="100">Dok.ID</th>
            <th width="100">Tanggal</th>
            <th width="150">Nama</th>
            <th width="300">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td><?php echo $row['m_nomor']; ?></td>
                    <td><?php echo $row['co_tgl'].' '.$row['co_jam']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                    <td><?php echo $row['m_keterangan']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
