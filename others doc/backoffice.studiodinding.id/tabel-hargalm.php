<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');

  	include "mssql-dbnew.php";

	$kdprog = $_SESSION['program'];
	$login = $_SESSION['loginid'];

	$gettgl = date("d/m/Y") ;
	$dumb1 = explode('/',$gettgl);	
	$nomor = $dumb1[2].$dumb1[1].$dumb1[0]; 

	$sqltgljam = "select convert(varchar(10),m_tanggal,103) as co_tgl, convert(varchar(10),m_tanggal,108) as co_jam from mshargalm where m_nomor = '".$nomor."'";
	$stmttgljam = sqlsrv_query($con_dbnew, $sqltgljam);
	$rowtgljam = sqlsrv_fetch_array( $stmttgljam, SQLSRV_FETCH_ASSOC);
	
?>
<table class="table table-bordered table-striped table-condensed">
    <thead>
        <tr>
            <th width="15%">Berat(gr)</td>
            <th width="10%"><div align="center">Jual</div></td>
            <th width="10%"><div align="center">Beli</div></td>
        </tr>
    </thead>
    <tbody>
	<?php
		$sqlbrg = "select m_kode, m_nama, m_kode2 from msmaster where m_type = 'ITEM' and left(m_kode2,2) = 'LM' order by m_kode asc";
		$stmtbrg = sqlsrv_query($con_dbnew, $sqlbrg);
		while( $rowbrg = sqlsrv_fetch_array( $stmtbrg, SQLSRV_FETCH_ASSOC))
		{
			$dumb = explode('-',$rowbrg['m_kode2']);
			$beli = 0 ;
			$jual = 0 ;
			
			if ((isset($_SESSION['loginid'])) && ($_SESSION['loginid'] <> ""))
			{
				$tsqlrate = "select a.m_beli, a.m_jual, a.m_modal from mshargalm2 a where a.m_nomor = '".$nomor."' and  a.m_kode = '".$rowbrg['m_kode']."'" ;
				$stmtrate = sqlsrv_query($con_dbnew, $tsqlrate);
				$rowrate = sqlsrv_fetch_array( $stmtrate, SQLSRV_FETCH_ASSOC);

				$beli = $rowrate['m_beli'] ;
				$jual = $rowrate['m_jual'] ;
				$modal= $rowrate['m_modal'] ;
				
				if ( $jual == 0 ) 
				{ $desc = '>> NA <<' ; }
				else
				{ $desc = number_format($jual, 0, '.', ',') ; }
				
			}
			?>
            <tr <?php if($jual == 0){ ?>style="color:#F00"<?php } ?> >
            	<td><?php echo $dumb[1]; ?></td>
            	<td><div align="right"><?php echo $desc ;   ?></div></td>
            	<td><div align="right"><?php echo number_format($beli, 0, '.', ',');   ?></div></td>
            </tr>
            <?php
		}
	?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" style="font-size:11px;font-style:italic;color:#F00">
                <?php echo 'Last Update : '.$rowtgljam['co_tgl'].' '.$rowtgljam['co_jam'] ;  ?>
            </th>
        </tr>
    </tfoot>
</table>
