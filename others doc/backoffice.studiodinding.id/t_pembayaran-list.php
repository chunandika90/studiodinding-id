<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$periode = $_GET['pr'];
	$scby = $_GET['by'];
	$sctx = $_GET['tx'];
	$prm = $_GET['prm'];
	
	$dumb = explode('-',$periode);

	$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl,
			 c.m_nama as nama_supplier
			 from t_pembayaran a, master_supplier c
			 where a.m_kode_supplier = c.m_kode and 
			 year(a.m_tanggal) = ".$dumb[0]." and month(a.m_tanggal) = ".$dumb[1] ;
	if (($scby != '') && ($sctx != ''))
	{ 
		if ($scby == 'nomor'){ $tsql = $tsql." and (a.m_nomor like '%".$sctx."%' or a.m_nama like '%".$sctx."%' 
											   or a.m_keterangan like '%".$sctx."%'  or b.m_nama like '%".$sctx."%' )   "; }
	}
	$tsql = $tsql." order by a.m_tanggal desc, a.m_nomor desc" ;
	
	//echo $tsql ."</br>";
	$stmt = $con_dbnew->query($tsql);

?>
<div style="overflow:auto;overflow-x:hidden;height:400px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="5"><h4>Invoice (Pembayaran)</h4></th>
        </tr>
        <tr>
            <th width="5">No</th>
            <th width="50">Nomor</th>
            <th width="50">Tanggal</th>
            <th width="50">Supplier</th>
            <th width="50">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
			
			$i = 0;
			while($row = $stmt->fetch_assoc())
            {
				$i = $i + 1;
                ?>
                <tr <?php if($row['m_status']== 'B'){ ?> style="color:#F00" <?php } ?> >
                    <td><?php echo $i; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_nomor']; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['co_tgl']; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['nama_supplier']; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_status']; ?></td>
                    
				</tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>