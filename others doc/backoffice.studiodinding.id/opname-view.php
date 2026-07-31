<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
    include "mssql-dbnew.php" ;
	$kdstore = $_GET['cb'];
	$nomor = $_GET['nm'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl from t_opname a where a.m_cabang = '".$kdstore."' and a.m_nomor = '".$nomor."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

?>
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="4"><h4><?php echo $kdstore.' - '.$nomor ; ?></h4></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="100">Nomor</td>
            <td width="150"><?php echo $row['m_nomor']; ?></td>
            <td width="75">Tanggal</td>
            <td width="150"><?php echo $row['co_tgl']; ?></td>
        </tr>
        <tr>
            <td>SO.ID</td>
            <td colspan="3"><?php echo $row['m_soid']; ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td colspan="3"><?php echo $row['m_nama']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>No</th>
            <th>Product ID</th>
            <th>Lokasi</th>
            <th>Qty</th>
            <th>Gross</th>
            <th>Net</th>
            <th>Butir</th>
            <th>Carat</th>
            <th>Harga</th>
            <th>No-Pic</th>
            <th>Bd-Pic</th>
            <th>Bd-Tag</th>
            <th>Ket.</th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0;
            $tsql2 = "	select 	a.*
                        from 	t_opname2 a
                        where 	a.m_cabang = '".$kdstore."' and 
                                a.m_nomor = '".$nomor."' " ;
            $stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
            while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
            {
				$sqldet = "select b.m_item, b.m_netweight,b.m_grossweight, b.m_butir, b.m_carat, b.m_harga from t_stockdata b where b.m_kodebarang = '".$row2['m_kodebarang']."' and m_productid = '".$row2['m_productid']."'";
            	$stmtdet = sqlsrv_query( $con_dbnew, $sqldet);
				$rowdet = sqlsrv_fetch_array( $stmtdet , SQLSRV_FETCH_ASSOC);
				$i = $i + 1;
                ?>
                <tr <?php if(substr($row2['m_lokasi'],0,2) != $kdstore ){ ?> style="color:#F00" <?php } ?> >
                    <td><?php echo $i; ?></td>
                    <td onClick="view_modal('<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')" style="cursor:pointer"><?php echo $row2['m_productid']; ?></td>
                    <td><?php echo $row2['m_lokasi']; ?></td>
                    <td><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($rowdet['m_grossweight'], 2, '.', ','); ?></td>
                    <td><?php echo number_format($rowdet['m_netweight'], 2, '.', ','); ?></td>
                    <td><div align="center"><?php echo number_format($rowdet['m_butir'], 0, '.', ','); ?></div></td>
                    <td><?php echo number_format($rowdet['m_carat'], 3, '.', ','); ?></td>
                    <td><div align="right"><?php echo number_format($rowdet['m_harga'], 0, '.', ','); ?></div></td>
                    <td><div align="center"><input type="checkbox" id="x_nopic<?php echo $i; ?>" name="X_nopic<?php echo $i; ?>" <?php if($row2['m_nopic']=='Y'){ ?> checked <?php } ?> disabled /></div></td>
                    <td><div align="center"><input type="checkbox" id="x_bedapic<?php echo $i; ?>" name="x_bedapic<?php echo $i; ?>" <?php if($row2['m_bedapic']=='Y'){ ?> checked <?php } ?> disabled /></div></td>
                    <td><div align="center"><input type="checkbox" id="x_bedabandrol<?php echo $i; ?>" name="x_bedabandrol<?php echo $i; ?>" <?php if($row2['m_bedabandrol']=='Y'){ ?> checked <?php } ?> disabled /></div></td>
                    <td><?php echo $row2['m_keterangan']; ?></td>
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="12">
            <div>
                <div class="pull-left" >
				<?php
                if (substr($xparam[3],1,1) == 'Y')
                {
                    ?>
                    <button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Edit</button>
                    <?php
				}
                if (substr($xparam[3],3,1) == 'Y')
                {
				?>
                    <button class="btn btn-warning" onclick="print_data('<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Print</button>
                    <?php
				}
				?>
                </div>
                <div class="pull-right" >
				<?php
                if (substr($xparam[3],2,1) == 'Y')
                {
                    ?>
                    <button class="btn btn-danger" onclick="hapus_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Batal Opname</button>
                    <?php
				}
				?>
                </div>  
            </div>
            </th>
        </tr>
    </tfoot>
</table>        
