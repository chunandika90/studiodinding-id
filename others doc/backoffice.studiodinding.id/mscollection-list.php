<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$kdsales = $_GET['sl'];
	$scby = $_GET['by'];
	$sctx = $_GET['tx'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	$tsql = "	select 	a.*, convert(varchar(10),a.m_tglmember,103) as co_tglmember, convert(varchar(10),a.m_tgllahir,103) as co_tgllahir
				from 	mscustomer a, msmaster b
				where 	a.m_cabang = b.m_kode and b.m_type = 'STORE' " ;
	if ($kdcabang != ''){ $tsql = $tsql." and a.m_cabang = '".$kdcabang."' "; }
	if ($kdsales != ''){ $tsql = $tsql." and a.m_kodesales = '".$kdsales."' "; }
	if (($scby != '') && ($sctx != ''))
	{ 
		if ($scby == 'kode'){ $tsql = $tsql." and a.m_kode like '".$sctx."%' "; }
		if ($scby == 'nama'){ $tsql = $tsql." and a.m_nama like '%".$sctx."%' "; }
		if ($scby == 'alamat'){ $tsql = $tsql." and a.m_alamat like '%".$sctx."%' "; }
		if ($scby == 'kota'){ $tsql = $tsql." and a.m_kota like '%".$sctx."%' "; }
		if ($scby == 'telepon'){ $tsql = $tsql." and a.m_telepon1 like '%".$sctx."%' "; }
	}
	$tsql = $tsql." order by a.m_nama asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
?>
<div style="overflow:auto;overflow-x:hidden;height:400px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
    	<tr>
            <th colspan="6">
            	<div class="pull-left"><h4>CUSTOMER - LIST</h4></div>
            </th>
        </tr>
        <tr>
            <th width="12%">Kode</th>
            <th width="17%">Nama</th>
            <th width="20%">Alamat</th>
            <th width="12%">Telepon</th>
            <th width="12%">Tgl.Lahir</th>
            <th width="10%">Store</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$tsqlsales = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
				$stmtsales = sqlsrv_query( $con_dbcmk, $tsqlsales);
				$rowsales = sqlsrv_fetch_array( $stmtsales, SQLSRV_FETCH_ASSOC);

				$tsqlcb = "select m_nama from msmaster where m_type = 'STORE' and  m_kode = '".$row['m_cabang']."'";
				$stmtcb = sqlsrv_query( $con_dbcmk, $tsqlcb);
				$rowcb = sqlsrv_fetch_array( $stmtcb, SQLSRV_FETCH_ASSOC);
				
                ?>
                <tr>
                    <td onclick="view_modal('<?php echo $row['m_kode']; ?>')" style="cursor:pointer"><?php echo $row['m_kode']; ?></td>                    
                    <td><?php echo $row['m_nama']; ?></td>
                    <td><?php echo $row['m_alamat'].'<br/>'.$row['m_kota']; ?></td>
                    <td><?php echo $row['m_telepon1'].'<br/>'.$row['m_telepon2']; ?></td>
                    <td><?php echo $row['m_tmplahir'].'<br/>'.$row['co_tgllahir']; ?></td>
                    <td><?php echo $row['m_cabang'].' - '.$rowcb['m_nama']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>