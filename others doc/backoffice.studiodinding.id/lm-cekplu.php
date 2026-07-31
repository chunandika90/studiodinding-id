<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$rowke = $_GET['rk'];
	$kdcab = $_GET['kdcab'];
	$kdlok = $_GET['kdlok'];
	$item = $_GET['vitem'];
	
	$tsql = "	select 	a.*, c.m_nama
				from 	t_stockinv a, t_stockdata b, msmaster c
				where 	a.m_cabang = '".$kdcab."' and 
						a.m_lokasi = '".$kdlok."' and 
						a.m_qty > 0 and
						a.m_kodebarang = 'M0000001' and 
						a.m_kodebarang = b.m_kodebarang and 
						a.m_productid = b.m_productid and 
						b.m_item = '".$item."' and
						c.m_type = 'ITEM' and 
						c.m_kode = b.m_item" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="30%">LM</th>
            <th width="10%">No.Sertifikat</th>
            <th width="5%">Qty</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td><?php echo $row['m_nama']; ?></td>
                    <td onClick="selectplu('<?php echo $rowke; ?>','<?php echo $row['m_productid']; ?>')" style="cursor:pointer"><?php echo $row['m_productid']; ?></td>
                    <td><?php echo number_format($row['m_qty'], 0, '.', ','); ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>


