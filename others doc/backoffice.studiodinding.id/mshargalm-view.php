<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php" ;
	$nomor = $_GET['nm'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);


	$tsql2 = "	select 	a.*, b.m_nama as namalm
				from 	mshargalm2 a, msmaster b
				where 	a.m_nomor = '".$nomor."' and 
						b.m_type = 'ITEM' and 
						a.m_kode = b.m_kode
				order by m_kode asc" ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	
?>
	<table class="table table-bordered table-striped table-hover table-condensed">
		<thead>
            <tr>
                <th rowspan="2" width="125">Desc</th>
                <th rowspan="2" width="100"><div align="center">Modal</div></th>
                <th colspan="3"><div align="center">MLI</div></th>
                <th colspan="2"><div align="center">ANTAM</div></th>
                <th rowspan="2" width="100"><div align="center">( +/- )</div></th>
            </tr>
            <tr>
                <th width="100"><div align="center">Beli</div></th>
                <th width="100"><div align="center">Jual B</div></th>
                <th width="100"><div align="center">Jual R</div></th>
                <th width="100"><div align="center">Beli</div></th>
                <th width="100"><div align="center">Jual</div></th>
            </tr>
		</thead>
		<tbody>
			<?php
				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
				{
					$style = '' ;
					if($row2['m_modal'] > $row2['m_jual']) 
					{
						$style = ' style="color:#F00"';
					}
					else if ($row2['m_jual'] > $row2['m_jual2'])
					{
						$style = ' style="color:#FF0"';
					}
					?>
                    <tr>
                        <td <?php echo $style; ?>><?php echo $row2['namalm']; ?></td>
                        <td><div align="right"><?php echo number_format($row2['m_modal'], 0, '.', ','); ?></div></td>
                        <td><div align="right"><?php echo number_format($row2['m_beli'], 0, '.', ','); ?></div></td>
                        <td><div align="right"><?php echo number_format($row2['m_jualb'], 0, '.', ','); ?></div></td>
                        <td><div align="right"><?php echo number_format($row2['m_jual'], 0, '.', ','); ?></div></td>
                        <td><div align="right"><?php echo number_format($row2['m_beli2'], 0, '.', ','); ?></div></td>
                        <td><div align="right"><?php echo number_format($row2['m_jual2'], 0, '.', ','); ?></div></td>
                        <td><div align="right"><?php echo number_format($row2['m_jual'] - $row2['m_jual2'], 0, '.', ','); ?></div></td>
                    </tr>
					<?php
				}
				?>
		</tbody>
        <tfoot>
        	<tr>
                <th colspan="8">
                <div>            
                    <div class="pull-left" >
                    <?php
                    if( substr($xparam[3],1,1) == 'Y' )
                    {
                        ?>
                        <button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Edit</button>
                        <?php
                    }
                    ?>
                    </div>
                    <?php
                    if( substr($xparam[3],2,1) == 'Y' )
                    {
                        ?>
                        <div class="pull-right" >
                            <button class="btn btn-danger" onclick="batal_data('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Delete</button>
                        </div>  
                        <?php
                    }
                    ?>
                </div>
                </th>
            </tr>
        </tfoot>
	</table>
