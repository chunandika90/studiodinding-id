<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$periode = isset($_GET['pr']) ? $_GET['pr'] : '';
	$scby = isset($_GET['by']) ? $_GET['by'] : '';
	$sctx = isset($_GET['tx']) ? $_GET['tx'] : '';
	$prm = isset($_GET['prm']) ? $_GET['prm'] : '';
	$approve = isset($_GET['approve']) ? $_GET['approve'] : 'belum_approved';
	
	$dumb = explode('-',$periode);

	$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl , b.supervisor_project as nama_supervisor, b.nama_project as nama_project,
			 case when a.m_jumlah_qty = a.m_terima_qty then 'Delivered' else 'Not Delivered' end m_terima, c.m_nama as nama_supplier,
			 case when a.m_approved_by is not null then 'Approved' else 'Belum Approved' end m_status_approved 
			 from t_po a, master_project b, master_supplier c
			 where a.m_kode_project = b.m_kode and a.m_kode_supplier = c.m_kode and 
			 year(a.m_tanggal) = ".$dumb[0]." and month(a.m_tanggal) = ".$dumb[1] ;
	if (($scby != '') && ($sctx != ''))
	{ 
		if ($scby == 'nomor'){ $tsql = $tsql." and (a.m_nomor like '%".$sctx."%' or a.m_nama like '%".$sctx."%' 
											   or a.m_keterangan like '%".$sctx."%'  or b.m_nama like '%".$sctx."%' )   "; }
	}
	// Filter status approval
	if ($approve == 'belum_approved') {
		$tsql .= " AND a.m_approved_by IS NULL";
	} elseif ($approve == 'approved') {
		$tsql .= " AND a.m_approved_by IS NOT NULL";
	}
	// kalau kosong (all) -> gak nambah kondisi apa2

	$tsql .= " ORDER BY a.m_tanggal DESC, a.m_nomor DESC";
	
	//echo $tsql ."</br>";
	$stmt = $con_dbnew->query($tsql);

?>
<div style="overflow:auto;overflow-x:hidden;height:700px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="5"><h4>PO (Supplier)</h4></th>
        </tr>
        <tr>
            <th>No</th>
			<th>Nomor</th>
			<th>Tanggal</th>
			<th>Nama </br> Projek</th>
			<th class="supplier">Vendor</th>
			<th class="hide-mobile">Status </br> Penerimaan</th>
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
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_nomor'] ."</br>".$row['m_status_approved'] ; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['co_tgl']; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['nama_project']; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer" class="supplier"><?php echo $row['nama_supplier']; ?></td>
                    <td  class="hide-mobile" onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_terima']; ?></td>
                    
				</tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>