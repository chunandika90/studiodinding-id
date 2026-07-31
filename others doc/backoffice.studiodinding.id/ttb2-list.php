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
	$tanggal1 = '01/'.$dumb[1].'/'.$dumb[0].' 00:00:00';
	$tanggal2 = '31/'.$dumb[1].'/'.$dumb[0].' 23:59:00';
	
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, b.m_nama as namasupplier, c.m_productid, c.m_rubberid,a.m_status 
			 from t_ttb a, mssupplier b , t_ttb2 c
			 where a.m_type ='I' and a.m_supplier = b.m_kode and a.m_nomor= c.m_nomor  " ;
	if ($kdstore != ''){ $tsql = $tsql." and a.m_cabang = '".$kdstore."' "; }
	if (($scby != '') && ($sctx != ''))
	{ 
		if ($scby == 'm_nomor'){ $tsql = $tsql." and a.m_nomor like '".$sctx."%' "; }
		if ($scby == 'm_rubberid'){ $tsql = $tsql." and c.m_rubberid like '".$sctx."%' "; }
		if ($scby == 'm_productid'){ $tsql = $tsql." and c.m_productid like '".$sctx."%' "; }
	}
	else
	{
		$tsql = $tsql." and year(a.m_tanggal)= '".$dumb[0]."'and month(a.m_tanggal)= '".$dumb[1]."' ";
	}
	$tsql = $tsql." order by a.m_cabang asc, a.m_tanggal desc, a.m_nomor desc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
//	echo $tsql ;
?>

<div style="overflow:auto;overflow-x:hidden;height:400px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="5"><h4>Inv.Receive (ADD-EDIT)</h4></th>
        </tr>
        <tr>
            <th width="100">Nomor</th>
            <th width="100">Product ID</th>
            <th width="100">Kode Barang</th>
            <th width="100">Tanggal</th>
            <th width="150">Supplier</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr <?php if($row['m_status']== 'B'){ ?> style="color:#F00" <?php } ?> >
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_cabang']; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_nomor']; ?></td>
                    <td><?php echo $row['m_productid']; ?></td>
                    <td><?php echo $row['m_rubberid']; ?></td>
                    <td><?php echo $row['co_tgl']; ?></td>
                    <td><?php echo $row['namasupplier']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>