<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php" ;
	$periode = $_GET['pr'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	if ( $periode =='' ){date("Y-m");}
	
	$dumb = explode('-',$periode);

	$tsql2 = "	select *, convert(varchar(10),m_tanggal,103) as co_tgl, convert(varchar(10),m_tanggal,108) as co_jam 
				from mshargalm 
				where year(m_tanggal) = ".$dumb[0]." and month(m_tanggal) = ".$dumb[1]." 
				order by m_tanggal desc" ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	
?>
<div style="overflow:auto;overflow-x:hidden;height:400px">
	<table class="table table-bordered table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th colspan="2"><h4>Harga LM</h4></th>
			</tr>
			<tr>
				<th width="10%">Tanggal</th>
				<th width="20%">Keterangan</th>
			</tr>
		</thead>
		<tbody>
			<?php
				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
				{
					?>
					<tr>
						<td onClick="oc_detail('<?php echo $prm ; ?>','<?php echo $row2['m_nomor']; ?>')" style="cursor:pointer"><?php echo $row2['co_tgl'].' '.$row2['co_jam']; ?></td>
						<td><div class="pull-right"><?php echo $row2['m_keterangan']; ?></div></td>
					</tr>
					<?php
				}
				?>
		</tbody>
	</table>
</div>