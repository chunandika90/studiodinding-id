<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$kdstore = $_GET['cb'];
	$periode = $_GET['pr'];
	$soid = $_GET['so'];
	$prm = $_GET['prm'];
	$scby = $_GET['by'];
	$txby = $_GET['tx'];
	
	$dumb = explode('-',$periode);

	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl from t_opname a where year(a.m_tanggal) = ".$dumb[0]." and month(a.m_tanggal) = ".$dumb[1] ;
	if ($kdstore != ''){ $tsql = $tsql." and a.m_cabang = '".$kdstore."' "; }
	if ($soid != ''){ $tsql = $tsql." and a.m_soid = '".$soid."' "; }
	if ($txby != '')
	{ 
		if ( $scby == 'nama' )
		{
			$tsql = $tsql." and a.m_nama like '%".$txby."%' ";
		}
		else if ( $scby == 'noplu' )
		{
			$tsql = $tsql." and a.m_nomor in (	select 	a.m_nomor 
												from 	t_opname a, t_opname2 b 
												where 	a.m_cabang = b.m_cabang and 
														a.m_nomor = b.m_nomor and 
														a.m_cabang = '".$kdstore."' and 
														a.m_soid = '".$soid."' and 
														year(a.m_tanggal) = ".$dumb[0]." and 
														month(a.m_tanggal) = ".$dumb[1]." and
														b.m_productid like '%".$txby."%') ";
		}
	}
	
	
	$tsql = $tsql." order by a.m_cabang asc, a.m_tanggal desc, a.m_nomor desc" ;
	
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
?>

<div style="overflow:auto;overflow-x:hidden;height:400px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="5"><h4>STOCK OPNAME (ADD-EDIT)</h4></th>
        </tr>
        <tr>
            <th width="80">Dok.ID</th>
            <th width="80">Tanggal</th>
            <th width="120">Nama</th>
            <th width="80">SO.ID</th>
            <th width="80">Pcs</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$cekpcs = "select count(*) as jumrow from t_opname2 where m_cabang = '".$row['m_cabang']."' and m_nomor = '".$row['m_nomor']."'";
				$stmtpcs = sqlsrv_query($con_dbnew, $cekpcs);
				$rowpcs = sqlsrv_fetch_array( $stmtpcs, SQLSRV_FETCH_ASSOC);
                ?>
                <tr <?php if($row['m_status']== 'B'){ ?> style="color:#F00" <?php } ?>>
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_cabang']; ?>','<?php echo $row['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row['m_nomor']; ?></td>
                    <td><?php echo $row['co_tgl']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                    <td><?php echo $row['m_soid']; ?></td>
                    <td><?php echo $rowpcs['jumrow']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>