<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$periode = $_GET['pr'];
	$sctx = $_GET['tx'];
	$prm = $_GET['prm'];
	$kode_project = $_GET['kode_project'];

	$group = $_SESSION['group'];
	$nama_spv = $_SESSION['nama'];

	// === pisah tahun-bulan kalau bukan "all"
	if ($periode != "all") {
		$dumb = explode('-', $periode);
		$wherePeriode = "year(a.m_tanggal) = ".$dumb[0]." and month(a.m_tanggal) = ".$dumb[1];
	} else {
		$wherePeriode = "1=1"; // tampil semua data
	}

	if ($group <> '03')
	{
		$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl, b.supervisor_project as nama_supervisor,
				 CASE 
					when a.m_partial <> 'OK' or a.m_partial is null 
						then case WHEN a.m_jumlah = a.m_po or a.m_jumlah < a.m_po THEN 'Complete' ELSE 'Not Complete' END 
						else 'Complete Partial' 
				 end status_bayar
				 from t_penawaran a, master_project b
				 where a.m_kode_project = b.m_kode 
				 and $wherePeriode";

		if ($sctx != '') { 
			$tsql .= " and (a.m_nomor like '%".$sctx."%' 
							or a.m_nama like '%".$sctx."%' 
							or a.m_keterangan like '%".$sctx."%'  
							or b.nama_project like '%".$sctx."%')"; 
		}
	}
	else
	{
		$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl, b.supervisor_project as nama_supervisor,
				 CASE 
					when a.m_partial <> 'OK' or a.m_partial is null 
						then case WHEN a.m_jumlah = a.m_po or a.m_jumlah < a.m_po THEN 'Complete' ELSE 'Not Complete' END 
						else 'Complete Partial' 
				 end status_bayar
				 from t_penawaran a, master_project b
				 where a.m_kode_project = b.m_kode 
				 and b.supervisor_project like '%".$nama_spv."%' 
				 and $wherePeriode";

		if ($sctx != '') { 
			$tsql .= " and (a.m_nomor like '%".$sctx."%' 
							or a.m_nama like '%".$sctx."%' 
							or a.m_keterangan like '%".$sctx."%'  
							or b.nama_project like '%".$sctx."%')"; 
		}
	}

	$tsql .= " order by a.m_tanggal desc, a.m_nomor desc";

	// echo $tsql ."</br>";
	$stmt = $con_dbnew->query($tsql);
	$sctx = $_GET['tx'];
	$prm = $_GET['prm'];
	$kode_project = $_GET['kode_project'];

	$group = $_SESSION['group'];
	$nama_spv = $_SESSION['nama'];

	// === pisah tahun-bulan kalau bukan "all"
	if ($periode != "all") {
		$dumb = explode('-', $periode);
		$wherePeriode = "year(a.m_tanggal) = ".$dumb[0]." and month(a.m_tanggal) = ".$dumb[1];
	} else {
		$wherePeriode = "1=1"; // tampil semua data
	}

	if ($group <> '03')
	{
		$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl, b.supervisor_project as nama_supervisor,
				 CASE 
					when a.m_partial <> 'OK' or a.m_partial is null 
						then case WHEN a.m_jumlah = a.m_po or a.m_jumlah < a.m_po THEN 'Complete' ELSE 'Not Complete' END 
						else 'Complete Partial' 
				 end status_bayar
				 from t_penawaran a, master_project b
				 where a.m_kode_project = b.m_kode 
				 and $wherePeriode";

		if ($sctx != '') { 
			$tsql .= " and (a.m_nomor like '%".$sctx."%' 
							or a.m_nama like '%".$sctx."%' 
							or a.m_keterangan like '%".$sctx."%'  
							or b.nama_project like '%".$sctx."%')"; 
		}
	}
	else
	{
		$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl, b.supervisor_project as nama_supervisor,
				 CASE 
					when a.m_partial <> 'OK' or a.m_partial is null 
						then case WHEN a.m_jumlah = a.m_po or a.m_jumlah < a.m_po THEN 'Complete' ELSE 'Not Complete' END 
						else 'Complete Partial' 
				 end status_bayar
				 from t_penawaran a, master_project b
				 where a.m_kode_project = b.m_kode 
				 and b.supervisor_project like '%".$nama_spv."%' 
				 and $wherePeriode";

		if ($sctx != '') { 
			$tsql .= " and (a.m_nomor like '%".$sctx."%' 
							or a.m_nama like '%".$sctx."%' 
							or a.m_keterangan like '%".$sctx."%'  
							or b.nama_project like '%".$sctx."%')"; 
		}
	}

	$tsql .= " order by a.m_tanggal desc, a.m_nomor desc";

	// echo $tsql ."</br>";
	$stmt = $con_dbnew->query($tsql);

?>
<div class="table-responsive" style="max-height:800px; overflow-y:auto;">
<table class="table table-bordered table-striped table-hover table-sm">
    <thead class="table-light">
        <tr>
            <th colspan="5"><h4>MR (Material Request)</h4></th>
        </tr>
        <tr>
            <th>Nomor</th>
			<th>Tanggal</th>
			<th>Project</th>
			<th>Supervisor</th>
			<th>Status </br> Pemenuhan </th>
        </tr>
    </thead>
    <tbody>
        <?php
			while($row = $stmt->fetch_assoc())
            {
                ?>
                <tr <?php if($row['m_status']== 'B'){ ?> style="color:#F00" <?php } ?> >
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_nomor']; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['co_tgl']; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_nama']; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['nama_supervisor']; ?></td>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['status_bayar']; ?></td>
                    
				</tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>