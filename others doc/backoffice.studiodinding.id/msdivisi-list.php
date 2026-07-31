<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$dept = $_GET['dp'];
	$scby = $_GET['by'];
	$sctx = $_GET['tx'];
	$prm = $_GET['prm'];
	
	
	$dumb = explode('-',$periode);

	$tsql = "select * from msdivisi a " ;
	//if ($kdstore != ''){ $tsql = $tsql." and a.m_cabang = '".$kdstore."' "; }
	if (($scby != '') && ($sctx != ''))
	{ 
		if ($scby == 'nomor'){ $tsql = $tsql." and a.m_nomor like '".$sctx."%' "; }
		if ($scby == 'nama'){ $tsql = $tsql." and a.m_nama like '".$sctx."%' "; }
	}
	if ($dept != '')
	{
		$tsql = $tsql." where m_dept = '".$dept."' order by a.m_kode asc" ;
	}
	else
	{
		$tsql = $tsql."  order by a.m_kode asc" ;
	}
	//echo $tsql;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);


?>
<div style="overflow:auto;overflow-x:hidden;height:500px">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="5"><h4>Kode Divisi</h4></th>
        </tr>
        <tr>
            <th width="60">Kode</th>
            <th width="60">Nama</th>
            <th width="120">Dept</th>
        </tr>
    </thead>
    <tbody>
        <?php
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				
				$tsqldept = " select * from msdept where m_kode = '".$row['m_dept']."' ";
				$stmtdept = sqlsrv_query( $con_dbnew, $tsqldept);
				$rowdept = sqlsrv_fetch_array( $stmtdept, SQLSRV_FETCH_ASSOC);
				
                ?>
                <tr <?php if($row['m_status']== 'B'){ ?> style="color:#F00" <?php } ?> >
                    <td onClick="oc_detail('<?php echo $prm; ?>','<?php echo $row['m_dept']; ?>','<?php echo $row['m_kode']; ?>')" style="cursor:pointer"><?php echo $row['m_kode']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                    <td><?php echo $rowdept['m_nama']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
</div>