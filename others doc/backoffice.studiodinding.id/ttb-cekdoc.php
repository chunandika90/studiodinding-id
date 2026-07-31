<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$sctx = $_GET['tx'];
	$hasil = '' ;
	//KRM/LD/14/04/0001
	
	if ( $sctx != '' )
	{
		// Cek dulu apakah udah pernak di input apa belum
		$tsqlcek = "select m_cabang, m_nomor from t_ttb where m_dokumen = '".$sctx."' and m_status = 'A'" ;
		$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek );
		$rowcek = sqlsrv_fetch_array($stmtcek, SQLSRV_FETCH_ASSOC) ;
		if ($rowcek['m_nomor'] == '')
		{
			$hasil = $sctx ;
			if (substr($sctx,0,6) == 'KRM/DJ')
			{
			$tsql = "	select 	c.nomor from palace_db.dbo.StockOutgoingDJ_Product a, palace_db.dbo.StockOutgoingDJ b, palace_db.dbo.StockActualDJ c 
						where 	b.nomor = '".$sctx."' and 
								a.IDForm = b.ID and 
								a.IDProduct = c.IDProduct and 
								b.ID not in (select IDOutGoing from palace_db.dbo.StockIncomingDJ where (Approval is not null and Approval <> '')) " ;
			}
			else if (substr($sctx,0,6) == 'KRM/PG')
			{
			$tsql = "	select 	c.nomor from palace_db.dbo.StockOutgoingPG_Product a, palace_db.dbo.StockOutgoingPG b, palace_db.dbo.StockActualPG c 
						where 	b.nomor = '".$sctx."' and 
								a.IDForm = b.ID and 
								a.IDProduct = c.IDProduct and 
								b.ID not in (select IDOutGoing from palace_db.dbo.StockIncomingPG where (Approval is not null and Approval <> '')) " ;
			}
			else if (substr($sctx,0,6) == 'KRM/LD')
			{
			$tsql = "	select 	c.nomor from palace_db.dbo.StockOutgoingLD_Product a, palace_db.dbo.StockOutgoingLD b, palace_db.dbo.StockActualLD_Stone1B c 
						where 	b.nomor = '".$sctx."' and 
								a.IDForm = b.ID and 
								a.IDProduct = c.IDProduct and 
								b.ID not in (select IDOutGoing from palace_db.dbo.StockIncomingLD where (Approval is not null and Approval <> '')) " ;
			}
			else if (substr($sctx,0,6) == 'KRM/GJ')
			{
			$tsql = "	select 	c.nomor from palace_db.dbo.StockOutgoingGJ_Product a, palace_db.dbo.StockOutgoingGJ b, palace_db.dbo.StockActualGJ c 
						where 	b.nomor = '".$sctx."' and 
								a.IDForm = b.ID and 
								a.IDProduct = c.IDProduct and 
								b.ID not in (select IDOutGoing from palace_db.dbo.StockIncomingGJ where (Approval is not null and Approval <> '')) " ;
			}
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			?>
            <table id="table-plu" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20%"><div align="center">No</div></th>
                        <th width="20%"><div align="center">Product-ID</div></th>
                        <th width="10%"><div align="center">Accept</div></th>
                        <th width="10%"><div align="center">Broken</div></th>
                        <th width="10%"><div align="center">Lost</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 0 ;
                        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
                        {
                            $i = $i + 1 ;
                            ?>
                            <tr>
                            	<td><?php echo number_format($i, 0, '.', ','); ?></td>
                                <td onClick="selectplu('<?php echo $row['nomor']; ?>')" style="cursor:pointer"><div align="center">
                                    <?php echo $row['nomor']; ?>
                                    <input type="hidden" id="m_noplu<?php echo $i; ?>" name="m_noplu<?php echo $i; ?>" value="<?php echo $row['nomor']; ?>" /></div>
                                </td>
                                <td><div align="center"><input type="checkbox" id="m_accept<?php echo $i; ?>" name="m_accept<?php echo $i; ?>" /></div></td>
                                <td><div align="center"><input type="checkbox" id="m_broken<?php echo $i; ?>" name="m_broken<?php echo $i; ?>" /></div></td>
                                <td><div align="center"><input type="checkbox" id="m_lost<?php echo $i; ?>" name="m_lost<?php echo $i; ?>" /></div></td>
                            </tr>
                            <?php
                        }
						if ( $i == 0 ) { $hasil = '' ; }
                        ?>
                </tbody>
            </table>
            <?php
		}
		else
		{
			echo 'ID Dokumen sudah diterima : '.$rowcek['m_cabang'].' - '.$rowcek['m_nomor'] ;
		}
	}
?>
<input type="hidden" id="cek_dok" name="cek_dok" value="<?php echo $hasil; ?>" />