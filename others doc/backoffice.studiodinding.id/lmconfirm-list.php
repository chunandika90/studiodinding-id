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
	$scby = $_GET['by'];
	$sctx = $_GET['tx'];
	
	$dumb = explode('-',$periode);

	$tsql = "	select 	a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl 
				from 	t_transfer a 
				where 	a.m_status = 'A' and 
						a.m_kodebarang = 'M0000001' and 
						year(a.m_tanggal) = ".$dumb[0]." and 
						month(a.m_tanggal) = ".$dumb[1] ;
	if ($kdstore != ''){ $tsql = $tsql." and left(a.m_lokasi2,2) = '".$kdstore."' "; }
	if (($scby != '') && ($sctx != ''))
	{ 
		if ($scby == 'nomor'){ $tsql = $tsql." and a.m_nomor like '".$sctx."%' "; }
		if ($scby == 'nama'){ $tsql = $tsql." and a.m_nama like '".$sctx."%' "; }
	}
	$tsql = $tsql." order by a.m_cabang asc, a.m_tanggal desc, a.m_nomor desc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
	//echo $tsql ;
?>
<div style="overflow:auto;overflow-x:hidden;height:400px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="5"><h4>INV TRANSFER ( CONFIRM )</h4></th>
        </tr>
        <tr>
            <th width="100">Dok.ID</th>
            <th width="100">Tanggal</th>
            <th width="150">Nama</th>
            <th width="50">Dari</th>
            <th width="50">Ke</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_cabang']; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_nomor']; ?></td>
                    <td><?php echo $row['co_tgl']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                    <td <?php if($row['m_confirm']!=''){ ?> style="background-color:#00B3B3" <?php } ?> ><?php echo $row['m_lokasi']; ?></td>
                    <td <?php if($row['m_confirm']!=''){ ?> style="background-color:#00B3B3" <?php } ?> ><?php echo $row['m_lokasi2']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>