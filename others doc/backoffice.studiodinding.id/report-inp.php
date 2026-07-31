<?php
  	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$kdstock = $_GET['kdst'];
	
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['cabang'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdstock ==''){$kdstock = 'ALL';}

	$tsql = "select * from dbo.f_reportin('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."')" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

?>


<style type="text/css" media="print, screen">
thead
{
	display:table-header-group;	
}
tbody
{
	display:table-row-group;	
}
</style>

	<table style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
    	<thead>
        	<tr>
            	<th colspan="12" align="left"><h2>LAPORAN PENERIMAAN (<?php echo $kdcabang;  ?>)</h2></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th style="border:1px solid #000" colspan="4">Keterangan</th>
            	<th width="75" style="border:1px solid #000">Qty</th>
            	<th width="100" style="border:1px solid #000">Total</th>
            	<th width="75" style="border:1px solid #000">Gross</th>
            	<th width="75" style="border:1px solid #000">Net</th>
            	<th width="75" style="border:1px solid #000">Butir</th>
            	<th width="75" style="border:1px solid #000">Carat</th>
            </tr>
        </thead>
        <tbody >
            <?php
			 $i = 0 ;
			$tqty = 0;
			$ttotal = 0;
			$tdisc = 0;
			$tpctdisc= 0;
			$tnetsl= 0;
			$tgross= 0;
			$tnet= 0;
			$tbutir= 0;
			$tcarat= 0;
			
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {	
				$i = $i + 1 ;
				
				$tampilanfont = '';
				if ($row['vf_level']=='1')
				{
					$tampilanfont = 'style="font-weight:bold"';
				}
				else if ($row['vf_level']=='2')
				{
					$tampilanfont = 'style="font-weight:bold;font-style:italic"';
				}
				else if ($row['vf_level']=='3')
				{
					$tampilanfont = 'style="font-style:italic"';
				}
				
                ?>
                <tr height="25" <?php echo $tampilanfont; ?> data-level="<?php echo $row['vf_level']; ?>" id="rowke<?php echo $i; ?>">
					<?php
                    if ($row['vf_level']=='1')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" align="left" colspan="4" width="350" ><?php echo $row['vf_nama']; ?></td>
                    <?php
                    }
                    else if ($row['vf_level']=='2')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" width="75"></td>
                        <td style="border-bottom:1px solid #000;border-top:1px solid #000" align="left" colspan="3" width="275"><?php echo $row['vf_nama']; ?></td>
                    <?php
                    }
                    else if ($row['vf_level']=='3')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" colspan="2" width="150"></td>
                        <td style="border-bottom:1px solid #000;border-top:1px solid #000" align="left" colspan="2" width="200"><?php echo $row['vf_nama']; ?></td>
                    <?php
                    }
                    else if ($row['vf_level']=='4')
                    {
                    ?>
                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-top:1px solid #000" colspan="3" width="225"></td>
                        <td style="border-bottom:1px solid #000;border-top:1px solid #000"" align="left" width="120"><?php echo $row['vf_nama']; ?></td>						
                    <?php
                    }
                    ?> 
                   <td style="border-left:1px solid #000;border:1px solid #000" align="center"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></td>
                   <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_total'], 0, '.', ','); ?></td>
                   <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_gross'], 2, '.', ','); ?></td>
                   <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_net'], 2, '.', ','); ?></td>
                   <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_butir'], 0, '.', ','); ?></td>
                   <td style="border-left:1px solid #000;border:1px solid #000" align="right"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></td>
                   
                   
                </tr>
            <?php
            }
            ?>
        	
        </tbody>
	</table>

