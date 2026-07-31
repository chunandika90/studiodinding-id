<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kdstore = $_GET['cb'];
	$periode = $_GET['pr'];
	$scby = $_GET['by'];
	$sctx = $_GET['tx'];
	$prm = $_GET['prm'];
	
	$dumb = explode('-',$periode);

	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl from t_pos a where m_type = 'J' and year(a.m_tanggal) = ".$dumb[0]." and month(a.m_tanggal) = ".$dumb[1] ;
	if ($kdstore != ''){ $tsql = $tsql." and a.m_cabang = '".$kdstore."' "; }
	if (($scby != '') && ($sctx != ''))
	{ 
		if ($scby == 'nomor'){ $tsql = $tsql." and a.m_nomor like '".$sctx."%' "; }
		if ($scby == 'nama'){ $tsql = $tsql." and a.m_nama like '".$sctx."%' "; }
	}
	$tsql = $tsql." order by a.m_cabang asc, a.m_tanggal desc, a.m_nomor desc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

?>
<div style="overflow:auto;overflow-x:hidden;height:400px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="5"><h4>POS (ADD-EDIT)</h4></th>
        </tr>
        <tr>
            <th width="60">Dok.ID</th>
            <th width="60">Tanggal</th>
            <th width="120">Nama</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr <?php if($row['m_status']== 'B'){ ?> style="color:#F00" <?php } ?> >
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_cabang']; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_nomor']; ?></td>
                    <td><?php echo $row['co_tgl']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>