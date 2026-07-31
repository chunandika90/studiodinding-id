<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$kdcust = $_GET['tx'];
	$rowke = $_GET['rowke'];

	$tsql = "	select	a.*, convert(varchar(10),b.m_tanggal,103) as co_tgl, convert(varchar(10),b.m_tanggal,108) as co_jam, c.m_item, d.m_nama as co_namabarang
				from	t_pos2 a, t_pos b, t_stockdata c, msbarang d
				where	a.m_cabang = b.m_cabang and
						a.m_nomor = b.m_nomor and 
						b.m_status = 'A' and 
						a.m_kodebarang = c.m_kodebarang and 
						a.m_productid = c.m_productid and
						a.m_kodebarang = d.m_kode and
						b.m_kodecust = '".$kdcust."' and 						
						a.m_productid not in ( 	select	x.m_productid
												from 	t_tradein2 x, t_pos y
												where	x.m_cabang = y.m_cabang and
														x.m_nomor = y.m_nomor and 
														y.m_status = 'A' )" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="10%">No.PLU</th>
            <th width="15%">Group</th>
            <th width="20%">Item</th>
            <th width="5%">Cabang</th>
            <th width="10%">No.Faktur</th>
            <th width="15%">Tanggal</th>
            <th width="10%">Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$tsqlitem = "select m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row['m_item']."'";
				$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
				$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
				
				$tnett = ($row['m_qty'] * $row['m_harga']) - $row['m_discount'] - $row['m_discount2'] - $row['m_discount3'] - $row['m_discount4']
                ?>
                <tr>
                    <td onClick="selectplu('<?php echo $rowke; ?>','<?php echo $row['m_kodebarang']; ?>','<?php echo $row['co_namabarang']; ?>','<?php echo  $rowitem['m_nama']; ?>','<?php echo $row['m_productid']; ?>','<?php echo $row['m_cabang']; ?>','<?php echo $row['m_nomor']; ?>','<?php echo $row['co_tgl'].' '.$row['co_jam']; ?>','<?php echo $tnett; ?>')" style="cursor:pointer"><?php echo $row['m_productid']; ?></td>
                    <td><?php echo $row['co_namabarang']; ?></td>
                    <td><?php echo $rowitem['m_nama']; ?></td>
                    <td><?php echo $row['m_cabang']; ?></td>
                    <td><?php echo $row['m_nomor']; ?></td>
                    <td><?php echo $row['co_tgl'].' '.$row['co_jam']; ?></td>
                    <td><div align="right"><?php echo number_format($row['m_harga'], 0, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>


